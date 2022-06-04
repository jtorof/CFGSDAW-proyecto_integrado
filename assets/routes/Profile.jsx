import React, { useContext, useEffect, useState } from 'react';
import {
  MDBCol,
  MDBRow,
  MDBBtn,
  MDBCollapse,
  MDBIcon,
  MDBContainer,
} from 'mdb-react-ui-kit';
import fetchData from '../helpers/fetchData';
import { UserContext } from '../helpers/context';

const Profile = () => {
  const context = useContext(UserContext);
  const [userInfo, setUserInfo] = useState({});
  const [showKey, setShowKey] = useState(false);

  const getUserInfo = async () => {
    try {
      const data = await fetchData('/user/operations/get-user-info', 'GET');
      if ("stats" in data) {
        setUserInfo(data);
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

  const toggleShowKey = () => setShowKey(!showKey);

  const addToClipboard = () => navigator.clipboard.writeText(userInfo.apiKey);

  const apiKeyRow = () => {
    if (!("apiKey" in userInfo)) {
      return (
        <MDBRow>
          <h3>API Key</h3>
        </MDBRow>
      );
    }
    if (userInfo.apiKey.length == 0) {
      <MDBRow>
        <h3>API Key <MDBBtn outline onClick={toggleShowKey}>{showKey ? "Ocultar" : "Mostrar"}</MDBBtn></h3>
        <MDBCollapse show={showKey}>
          <p>Aún no has tienes una API Key - <MDBBtn outline onClick={generateApiKey}>Generar</MDBBtn></p>
        </MDBCollapse>
      </MDBRow>
    }
    return (
      <MDBRow>
        <h3>API Key <MDBBtn outline onClick={toggleShowKey}>{showKey ? "Ocultar" : "Mostrar"}</MDBBtn></h3>
        <MDBCollapse show={showKey}>
          <p>{userInfo.apiKey} <MDBBtn floating color='dark' onClick={addToClipboard}><MDBIcon fas icon="clipboard-check" size='lg' /></MDBBtn></p>
        </MDBCollapse>
      </MDBRow>)
  };

  useEffect(() => {
    getUserInfo();
  }, []);

  return (
    <>
      <h2>
        Perfil de Usuario - {context.globalUser?.email}
      </h2>
      <MDBRow>
        <h3>Estadísticas de peticiones</h3>
        <MDBCol md="6">
          <p>Peticiones GET: {userInfo.stats?.getCount}</p>
          <p>Peticiones POST: {userInfo.stats?.postCount}</p>
          <p>Peticiones DELETE: {userInfo.stats?.deleteCount}</p>
        </MDBCol>
        <MDBCol md="6">
          <p>Peticiones PUT: {userInfo.stats?.putCount}</p>
          <p>Peticiones PATCH: {userInfo.stats?.patchCount}</p>
        </MDBCol>
      </MDBRow>
      {apiKeyRow()}
    </>
  )
}

export default Profile