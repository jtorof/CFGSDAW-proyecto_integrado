import React, { useContext, useEffect, useState } from 'react';
import { useLocation, Navigate } from 'react-router-dom';
import { UserContext } from '../helpers/context';

const RequireNotAuth = ({ children }) => {
  const context = useContext(UserContext);
  const location = useLocation();
  const [awaitingContext, setAwaitingContext] = useState(true);

  useEffect(() => {
    if (context) {
      setAwaitingContext(false);
    }
  }, [])

  if (awaitingContext) {
    return null
  }

  if (!("email" in context.globalUser)) {
    return (
      children
    )
  }

  return (
    <Navigate to="/perfil" replace />
  );
}

export default RequireNotAuth