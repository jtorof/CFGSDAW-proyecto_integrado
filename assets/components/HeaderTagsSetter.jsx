import React from 'react';
import { Helmet } from 'react-helmet';
import { useLocation } from 'react-router-dom';
import routesNamesMap from '../helpers/routes';


const HeaderTagsSetter = () => {
  const location = useLocation();
  const routeArray = location.pathname.split("/");
  const lastRoute = routeArray[routeArray.length - 1];

  return (
    <Helmet>
      <title>{ routesNamesMap.get(lastRoute) ? routesNamesMap.get(lastRoute) : `No encontrado` } | APIParaPracticar</title>
    </Helmet>
  )
}

export default HeaderTagsSetter