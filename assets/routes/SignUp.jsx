import React from 'react';
import { Helmet } from "react-helmet";
import SignUpForm from '../components/SignUpForm';

const SignUp = () => {
  return (
    <>
      <Helmet>
        <title>APIParaPracticar | Crear cuenta</title>
      </Helmet>
      <SignUpForm />
    </>
  )
}

export default SignUp