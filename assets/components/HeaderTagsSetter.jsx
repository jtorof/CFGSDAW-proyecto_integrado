import React from 'react';
import { Helmet } from 'react-helmet';
import { useLocation } from 'react-router-dom';
import routesNamesMap from '../helpers/routes';


const HeaderTagsSetter = () => {
  const location = useLocation();
  const route = location.pathname.substring(1);

  return (
    <Helmet>
      <title>{ routesNamesMap.get(route) ? routesNamesMap.get(route) : `No encontrado` } | APIParaPracticar</title>
    </Helmet>
  )
}

export default HeaderTagsSetter