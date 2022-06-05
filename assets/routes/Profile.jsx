import React, { useContext, useEffect, useState } from 'react';
import {
  MDBCol,
  MDBRow,
  MDBBtn,
  MDBCollapse,
  MDBIcon,
  MDBSpinner,
  MDBContainer,
} from 'mdb-react-ui-kit';
import Alert from 'react-bootstrap/Alert';
import fetchData from '../helpers/fetchData';
import { UserContext } from '../helpers/context';

const subscriptionUrl = new URL(`http://localhost:59500/.well-known/mercure`);

const Profile = () => {
  const context = useContext(UserContext);
  const [dataIsReturned, setDataIsReturned] = useState(false);
  const [awaitingResponse, setAwaitingResponse] = useState(false);
  const [userApiKey, setUserApiKey] = useState("");
  const [userApiKeyIsEnabled, setUserApiKeyIsEnabled] = useState(true);
  const [userStats, setUserStats] = useState("");
  const [showKey, setShowKey] = useState(false);
  const [topic, setTopic] = useState("");

  const getUserInfo = async () => {
    try {
      const data = await fetchData('/user/operations/get-user-info', 'GET');
      console.log(data);
      if ("stats" in data) {
        setUserApiKey(data.apiKey);
        setUserApiKeyIsEnabled(data.apiKeyIsEnabled);
        setUserStats(data.stats);
        setDataIsReturned(true);
      }
    } catch (error) {
      console.log(error);
    }
  }

  const generateApiKey = async () => {
    try {
      const data = await fetchData('/user/operations/generate-api-key', 'GET');
      if (("message" in data) && data.message === "Api Key generada") {
        getUserInfo(data);
        setShowKey(true);
      }
    } catch (error) {
      console.log(error);
    }
  }

  const enableApiKey = async () => {
    try {
      setAwaitingResponse(true);
      const data = await fetchData('/user/operations/reset-rate-limiter', 'GET');
      if (("message" in data) && data.message === "Ya puede volver a acceder") {
        setUserApiKeyIsEnabled(true);
        setAwaitingResponse(false);
      }
    } catch (error) {
      console.log(error);
    }
  }

  const toggleShowKey = () => setShowKey(!showKey);

  const addToClipboard = () => navigator.clipboard.writeText(userInfo.apiKey);

  const apiKeyRow = () => {
    if (userApiKey && userApiKey.length === 0) {
      return (
        <MDBRow>
          <h3>API Key</h3>
          <Alert
            variant='warning'
            className='d-flex align-items-center justify-content-center'
          >
            <p className='mb-0 me-3'>Aún no has tienes una API Key </p><MDBBtn outline onClick={generateApiKey}>Generar</MDBBtn>
          </Alert>
        </MDBRow>
      )
    }
    return (
      <MDBRow>
        <h3>API Key <MDBBtn outline onClick={toggleShowKey}>{showKey ? "Ocultar" : "Mostrar"}</MDBBtn></h3>
        <MDBCollapse show={showKey}>
          <p>{userApiKey} <MDBBtn floating color='dark' onClick={addToClipboard}><MDBIcon fas icon="clipboard-check" size='lg' /></MDBBtn></p>
        </MDBCollapse>
      </MDBRow>)
  };

  const enableApiKeyRow = () => {
    if (userApiKeyIsEnabled) {
      return null
    }
    return (
      <>
        <MDBRow>
          <Alert
            variant='danger'
            className='d-flex align-items-center justify-content-center'
          >
            <p className='mb-0 me-3'>API Key desactivada por exceso de peticiones. Puede esperar o bien: </p><MDBBtn outline onClick={enableApiKey}>Habilitar</MDBBtn>
          </Alert>
        </MDBRow>
        <MDBRow>
          <MDBCollapse show={awaitingResponse}>
            <MDBContainer className='d-flex align-items-center justify-content-center'>
              <MDBSpinner role='status'>
                <span className='visually-hidden'>Cargando...</span>
              </MDBSpinner>
            </MDBContainer>
          </MDBCollapse>
        </MDBRow>
      </>
    )
  }

  const contentToRender = () => {
    if (dataIsReturned) {
      return (
        <>
          <MDBRow>
            <h2>
              Perfil de Usuario - {context.globalUser?.email}
            </h2>
          </MDBRow>
          <MDBRow>
            <h3>Estadísticas de peticiones</h3>
            <MDBCol md="6">
              <p>Peticiones GET: {userStats.getCount}</p>
              <p>Peticiones POST: {userStats.postCount}</p>
              <p>Peticiones DELETE: {userStats.deleteCount}</p>
            </MDBCol>
            <MDBCol md="6">
              <p>Peticiones PUT: {userStats.putCount}</p>
              <p>Peticiones PATCH: {userStats.patchCount}</p>
            </MDBCol>
          </MDBRow>
          {enableApiKeyRow()}
          {apiKeyRow()}
        </>
      );
    }
    return (
      <>
        <MDBRow>
          <h2>
            Perfil de Usuario - {context.globalUser?.email}
          </h2>
        </MDBRow>
        <MDBRow className='d-flex align-items-center justify-content-center'>
          <MDBSpinner role='status'>
            <span className='visually-hidden'>Cargando...</span>
          </MDBSpinner>
        </MDBRow>
      </>
    );
  }

  useEffect(() => {
    getUserInfo();
    if (context.globalUser?.email) {
      // setTopic(context.globalUser?.email);
      setTopic(`apiparapracticar.com/user/${context.globalUser.email}`);
    }
  }, []);

  useEffect(() => {
    subscriptionUrl.searchParams.append(`topic`, topic);
    const eventSource = new EventSource(subscriptionUrl);
    eventSource.onmessage = function ({ data }) {
      const parsedData = JSON.parse(data);
      setUserApiKeyIsEnabled(parsedData.apiKeyIsEnabled);
      setUserStats(parsedData.stats);
    };

    return () => {
      eventSource.close();
    }
  }, [topic]);


  return (
    contentToRender()
  )
}

export default Profile