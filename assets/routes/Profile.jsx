import React, { useContext, useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
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
import { CopyBlock, nord } from 'react-code-blocks';
import fetchData from '../helpers/fetchData';
import { UserContext } from '../helpers/context';
import Countdown from 'react-countdown';

const subscriptionUrl = new URL(`https://apiparapracticar.ddns.net/mercure-hub/.well-known/mercure`);

const Profile = () => {
  const context = useContext(UserContext);
  const [showEverything, setShowEverything] = useState(false);
  const [awaitingResponse, setAwaitingResponse] = useState(false);
  const [userApiKey, setUserApiKey] = useState("");
  const [userApiKeyIsEnabled, setUserApiKeyIsEnabled] = useState(true);
  const [userRetryAfter, setUserRetryAfter] = useState(0);
  const [userStats, setUserStats] = useState("");
  const [userHasApiDataCopy, setUserHasApiDataCopy] = useState(true);
  const [showKey, setShowKey] = useState(false);
  const [topic, setTopic] = useState("");

  // const getUserInfo = async () => {
  //   try {
  //     const data = await fetchData('/user/operations/get-user-info', 'GET');
  //     console.log(data);
  //     if ("stats" in data) {
  //       setUserApiKey(data.apiKey);
  //       setUserApiKeyIsEnabled(data.apiKeyIsEnabled);
  //       setUserStats(data.stats);
  //     }
  //   } catch (error) {
  //     console.log(error);
  //   }
  // }

  const generateApiKey = async () => {
    try {
      const data = await fetchData('/user/operations/generate-api-key', 'POST');
      if (("message" in data) && data.message === "Api Key generada") {
        // getUserInfo(data);
        setShowKey(true);
      }
    } catch (error) {
      console.log(error);
    }
  }

  const enableApiKey = async (showLoading = true) => {
    try {
      // setUserRetryAfter(null);
      if (showLoading) {
        setAwaitingResponse(true);
      }
      const data = await fetchData('/user/operations/reset-rate-limiter', 'POST');
      if (("message" in data) && data.message === "Ya puede volver a acceder") {
        // setUserApiKeyIsEnabled(true);
        if (showLoading) {
          setAwaitingResponse(false);
        }
      }
    } catch (error) {
      console.log(error);
    }
  }

  const generateData = async () => {
    try {
      const data = await fetchData('/user/operations/generate-data', 'POST');
      // if (("message" in data) && data.message === "Ya dispone de los datos") {
      //   setUserHasApiDataCopy(true);
      // }
    } catch (error) {
      console.log(error);
    }
  }

  const toggleShowKey = () => setShowKey(!showKey);

  const titleRow = () => {
    if (!userHasApiDataCopy) {
      return (
        <MDBRow>
          <h2>
            Perfil de Usuario - {context.globalUser?.email}
          </h2>
        </MDBRow>
      )
    } else {
      return (
        <MDBRow>
          <h2 className='d-flex justify-content-between'>
            Perfil de Usuario - {context.globalUser?.email}&nbsp;
            <MDBBtn outline color='danger' tag={Link} to='opciones-avanzadas'>
              <MDBIcon fas icon="cog" /> Opciones Avanzadas
            </MDBBtn>
          </h2>
        </MDBRow>
      )
    }
  }

  const statsRow = () => {
    if (!userStats || !("getCount" in userStats)) {
      return null
    }
    return (
      <MDBRow>
        <h3 className='d-flex justify-content-between'>Estadísticas de peticiones</h3>
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
    )
  }

  const apiKeyRow = () => {
    if (userApiKey.length === 0) {
      return (
        <MDBRow>
          <h3 className='d-flex justify-content-between'>API Key</h3>
          <Alert
            variant='info'
            className='d-flex align-items-center justify-content-center'
          >
            <p className='mb-0 me-3'>Aún no has tienes una API Key </p><MDBBtn outline onClick={generateApiKey}>Generar</MDBBtn>
          </Alert>
        </MDBRow>
      )
    }
    return (
      <MDBRow>
        <h3 className='d-flex justify-content-between'>API Key <MDBBtn outline onClick={toggleShowKey}>{showKey ? "Ocultar" : "Mostrar"}</MDBBtn></h3>
        <MDBCollapse show={showKey}>
          <CopyBlock
            text={userApiKey}
            showLineNumbers={false}
            wrapLongLines={true}
            codeBlock={true}
            language="html"
            theme={nord}
          />
        </MDBCollapse>
      </MDBRow>)
  };

  const renderer = ({ hours, minutes, seconds, completed }) => {
    if (completed) {
      // Render a completed state
      // console.log(completed);
      // enableApiKey();
      enableApiKey(false);
      return null;
    } else {
      // Render a countdown
      return <span>{hours}:{minutes}:{seconds} </span>;
    }
  };

  const enableApiKeyRow = () => {
    if (userApiKeyIsEnabled || userApiKeyIsEnabled == null) {
      return null
    }
    return (
      <>
        <MDBRow>
          <Alert
            variant='danger'
            className='d-flex align-items-center justify-content-center'
          >
            <p className='mb-0 me-3'>API Key desactivada por exceso de peticiones. Puede esperar&nbsp;
              {userRetryAfter === null ? null : <Countdown
                date={Date.now() + userRetryAfter * 1000}
                renderer={renderer}
              />}
              o bien: </p><MDBBtn outline onClick={enableApiKey}>Habilitar</MDBBtn>
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
    if (userHasApiDataCopy) {
      return (
        <>
          {titleRow()}
          {enableApiKeyRow()}
          {statsRow()}
          {apiKeyRow()}
        </>
      );
    }
    return (
      <>
        {titleRow()}
        <MDBRow className='d-flex align-items-center justify-content-center'>
          <Alert
            variant='info'
            className='d-flex align-items-center justify-content-center'
          >
            <p className='mb-0 me-3'>Aún no ha obtenido su copia de los datos, que necesita para poder utilizar el servicio.</p><MDBBtn outline onClick={generateData}>Obtener datos</MDBBtn>
          </Alert>
        </MDBRow>
      </>
    );
  }

  useEffect(() => {
    // console.log(context.globalUser);
    // console.log(context.globalUserInfo);
    if (("email" in context.globalUser) && (context.globalUserInfo !== null)) {
      setTopic(`apiparapracticar.com/user/${context.globalUser.email}`);
      setUserApiKey(context.globalUserInfo.apiKey);
      setUserApiKeyIsEnabled(context.globalUserInfo.apiKeyIsEnabled);
      setUserRetryAfter(context.globalUserInfo.retryAfter);
      setUserStats(context.globalUserInfo.stats);
      setUserHasApiDataCopy(context.globalUserInfo.userHasApiDataCopy);
      setShowEverything(true);
    }
  }, []);

  useEffect(() => {
    subscriptionUrl.searchParams.append(`topic`, topic);
    const eventSource = new EventSource(subscriptionUrl);
    eventSource.onmessage = function ({ data }) {
      const parsedData = JSON.parse(data);
      // console.log(parsedData);
      setUserHasApiDataCopy(parsedData.userHasApiDataCopy);
      setUserApiKey(parsedData.apiKey);
      setUserApiKeyIsEnabled(parsedData.apiKeyIsEnabled);
      setUserRetryAfter(parsedData.retryAfter);
      setUserStats(parsedData.stats);
      if (awaitingResponse) {
        setAwaitingResponse(false); 
      }
    };

    return () => {
      eventSource.close();
    }
  }, [topic]);


  return (
    showEverything ? contentToRender() : null
  )
}

export default Profile