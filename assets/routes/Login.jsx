import React from 'react';
import LoginForm from '../components/LoginForm';

const Login = ({ prevLocation }) => {
  return (
    <>
      <LoginForm prevLocation={ prevLocation }/>
    </>
  )
}

export default Login