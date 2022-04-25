import React from 'react'
import { useNavigate, useLocation } from 'react-router-dom';

const AccessRestricted = () => {
  const navigate = useNavigate();
  const location = useLocation();
  return (
    <div>AccessRestricted</div>
  )
}

export default AccessRestricted