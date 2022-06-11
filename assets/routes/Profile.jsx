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

const subscriptionUrl = new URL(`http://localhost:59500/.well-known/mercure`);

const Profile = () => {
  const context = useContext(UserContext);
  const [showEverything, setShowEverything] = useState(false);
  const [awaitingResponse, setAwaitingResponse] = useState(false);
  const [apiKey, setApiKey] = useState("");
  const [apiKeyIsEnabled, setApiKeyIsEnabled] = useState(true);
  const [retryAfter, setRetryAfter] = useState(0);
  const [stats, setStats] = useState("");
  const [userHasApiDataCopy, setUserHasApiDataCopy] = useState(false);
  const [showKey, setShowKey] = useState(false);
  const [showAlert, setShowAlert] = useState(false);
  const [topic, setTopic] = useState("");

  // const getUserInfo = async () => {
  //   try {
  //     const data = await fetchData('/user/operations/get-user-info', 'GET');
  //     console.log(data);
  //     if ("stats" in data) {
  //       setApiKey(data.apiKey);
  //       setApiKeyIsEnabled(data.apiKeyIsEnabled);
  //       setStats(data.stats);
  //     }
  //   } catch (error) {
  //     console.log(error);
  //     setShowAlert(true);
  //   }
  // }

  const generateApiKey = async () => {
    setShowAlert(false);
    setAwaitingResponse(true);
    try {
      const data = await fetchData('/user/operations/generate-api-key', 'POST');
      if (("message" in data) && data.message === "Api Key generada") {
        // getUserInfo(data);
        setShowKey(true);
      } else {
        setShowAlert(true);
      }
    } catch (error) {
      setShowAlert(true);
      // console.log(error);
    }
    setAwaitingResponse(false);
  }

  const enableApiKey = async (showLoading = true) => {
    setShowAlert(false);
    try {
      // setRetryAfter(null);
      if (showLoading) {
        setAwaitingResponse(true);
      }
      const data = await fetchData('/user/operations/reset-rate-limiter', 'POST');
      if (("message" in data) && data.message === "Ya puede volver a acceder") {
        // setApiKeyIsEnabled(true);
        if (showLoading) {
          setAwaitingResponse(false);
        }
      } else {
        setShowAlert(true);
      }
    } catch (error) {
      setShowAlert(true);
      // console.log(error);
    }
  }

  const generateData = async () => {
    setShowAlert(false);
    setAwaitingResponse(true);
    try {
      const data = await fetchData('/user/operations/generate-data', 'POST');
      if (("message" in data) && data.message === "Ya dispone de los datos") { //Legacy, left in case we go back to not using mercure
        // setUserHasApiDataCopy(true);
        // return;
      } else {
        setShowAlert(true);
      }
    } catch (error) {
      setShowAlert(true);
      // console.log(error);
    }
    setAwaitingResponse(false);
  }

  const toggleShowKey = () => setShowKey(!showKey);

  const awaitingResponseRow = () => {
    if (awaitingResponse) {
      return (
        <MDBRow className='my-0'>
          <MDBCollapse show={true}>
            <MDBContainer className='d-flex align-items-center justify-content-center'>
              <MDBSpinner role='status'>
                <span className='visually-hidden'>Cargando...</span>
              </MDBSpinner>
            </MDBContainer>
          </MDBCollapse>
        </MDBRow>
      )
    }
    return null;
  }

  const titleRow = () => {
    if (!userHasApiDataCopy) {
      return (
        <MDBRow className='mb-0'>
          <h2>
            Perfil de Usuario - {context.globalUser?.email}
          </h2>
        </MDBRow>
      )
    } else {
      return (
        <MDBRow className='mb-0'>
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

  const alertRow = () => {
    if (!showAlert) {
      return null
    }
    return (
      <MDBRow className='my-0'>
        <Alert
          variant='danger'
          className='d-flex align-items-center justify-content-center'
        >
          Ha habido un error
        </Alert>
      </MDBRow>
    )
  }

  const statsRow = () => {
    if (!stats || !("getCount" in stats)) {
      return null
    }
    return (
      <MDBRow className='mt-3'>
        <h3 className='d-flex justify-content-between'>Estadísticas de peticiones</h3>
        <MDBCol md="6">
          <p>Peticiones GET: {stats.getCount}</p>
          <p>Peticiones POST: {stats.postCount}</p>
          <p>Peticiones DELETE: {stats.deleteCount}</p>
        </MDBCol>
        <MDBCol md="6">
          <p>Peticiones PUT: {stats.putCount}</p>
          <p>Peticiones PATCH: {stats.patchCount}</p>
        </MDBCol>
      </MDBRow>
    )
  }

  const apiKeyRow = () => {
    if (apiKey.length === 0) {
      return (
        <>
          <MDBRow className='mb-0'>
            <h3 className='d-flex justify-content-between'>API Key</h3>
            <Alert
              variant='info'
              className='d-flex align-items-center justify-content-center'
            >
              <p className='mb-0 me-3'>Aún no has tienes una API Key </p>
              <MDBBtn outline onClick={generateApiKey}>
                <MDBIcon fas icon="plus-square" />
                &nbsp;Generar
              </MDBBtn>
            </Alert>
          </MDBRow>
          {awaitingResponseRow()}
        </>
      )
    }
    return (
      <MDBRow>
        <h3 className='d-flex justify-content-between'>
          API Key
          <MDBBtn outline onClick={toggleShowKey}>
            <MDBIcon fas icon={showKey ? "eye-slash" : "eye"} />
            &nbsp;{showKey ? "Ocultar" : "Mostrar"}
          </MDBBtn>
        </h3>
        <MDBCollapse show={showKey}>
          <CopyBlock
            text={apiKey}
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
    if (apiKeyIsEnabled || apiKeyIsEnabled == null) {
      return null
    }
    return (
      <>
        <MDBRow className='my-0'>
          <Alert
            variant='danger'
            className='d-flex align-items-center justify-content-center'
          >
            <p className='mb-0 me-3'>API Key desactivada por exceso de peticiones. Puede esperar&nbsp;
              {retryAfter === null ? null : <Countdown
                date={Date.now() + retryAfter * 1000}
                renderer={renderer}
              />}
              o bien: </p><MDBBtn outline onClick={enableApiKey}>Habilitar</MDBBtn>
          </Alert>
        </MDBRow>
        {awaitingResponseRow()}
      </>
    )
  }

  const contentToRender = () => {
    if (userHasApiDataCopy) {
      return (
        <>
          {titleRow()}
          {alertRow()}
          {enableApiKeyRow()}
          {statsRow()}
          {apiKeyRow()}
        </>
      );
    }
    return (
      <>
        {titleRow()}
        {alertRow()}
        <MDBRow className='my-0'>
          <Alert
            variant='info'
            className='d-flex align-items-center justify-content-center'
          >
            <p className='mb-0 me-3'>Aún no ha obtenido su copia de los datos, que necesita para poder utilizar el servicio.</p>
            <MDBBtn outline onClick={generateData}>
              <MDBIcon fas icon="clone" />
              &nbsp;Obtener datos
            </MDBBtn>
          </Alert>
        </MDBRow>
        {awaitingResponseRow()}
      </>
    );
  }

  useEffect(() => {
    // console.log(context.globalUser);
    // console.log(context.globalUserInfo);
    if (("email" in context.globalUser) && (context.globalUserInfo !== null)) {
      // console.log("settting state from context");
      setTopic(`apiparapracticar.com/user/${context.globalUser.email}`);
      setApiKey(context.globalUserInfo.apiKey);
      setApiKeyIsEnabled(context.globalUserInfo.apiKeyIsEnabled);
      setRetryAfter(context.globalUserInfo.retryAfter);
      setStats(context.globalUserInfo.stats);
      setUserHasApiDataCopy(context.globalUserInfo.userHasApiDataCopy);
      setShowEverything(true);
    }
  }, []);

  useEffect(() => {
    if (topic.length === 0) {
      return
    }
    subscriptionUrl.searchParams.append(`topic`, topic);
    const eventSource = new EventSource(subscriptionUrl);
    eventSource.onmessage = function ({ data }) {
      const parsedData = JSON.parse(data);
      // console.log(parsedData);
      setUserHasApiDataCopy(parsedData.userHasApiDataCopy);
      setApiKey(parsedData.apiKey);
      setApiKeyIsEnabled(parsedData.apiKeyIsEnabled);
      setRetryAfter(parsedData.retryAfter);
      setStats(parsedData.stats);
      // if (awaitingResponse) {
      setAwaitingResponse(false);
      // }
    };

    return () => {
      eventSource.close();
    }
  }, [topic]);

  useEffect(() => {
    context.setGlobalUserInfo({
      ...context.globalUserInfo,
      apiKey: apiKey
    });
  }, [apiKey])

  useEffect(() => {
    context.setGlobalUserInfo({
      ...context.globalUserInfo,
      apiKeyIsEnabled: apiKeyIsEnabled
    });
  }, [apiKeyIsEnabled])

  useEffect(() => {
    context.setGlobalUserInfo({
      ...context.globalUserInfo,
      userHasApiDataCopy: userHasApiDataCopy
    });
  }, [userHasApiDataCopy])

  useEffect(() => {
    context.setGlobalUserInfo({
      ...context.globalUserInfo,
      retryAfter: retryAfter
    });
  }, [retryAfter])

  useEffect(() => {
    context.setGlobalUserInfo({
      ...context.globalUserInfo,
      stats: stats
    });
  }, [stats])

  return (
    showEverything ? contentToRender() : null
  )
}

export default Profile