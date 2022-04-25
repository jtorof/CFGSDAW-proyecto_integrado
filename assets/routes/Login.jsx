import React from 'react';
import { Helmet } from "react-helmet";
import LoginForm from '../components/LoginForm';

const Login = () => {
  return (
    <>
      <Helmet>
        <title>APIParaPracticar | Iniciar Sesión</title>
      </Helmet>
      <LoginForm />
    </>
  )
}

export default Login