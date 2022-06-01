import React, { useContext } from 'react';
import { useLocation, Navigate } from 'react-router-dom';
import { UserContext } from '../helpers/context';

const RequireAuth = ({ children }) => {
  const context = useContext(UserContext);
  const location = useLocation();  

  if(!("email" in context.globalUser)){
    return (
      <Navigate to="/login" prevLocation={ location.pathname } replace />
    );
  }

  return (
    children
  )
}

export default RequireAuth