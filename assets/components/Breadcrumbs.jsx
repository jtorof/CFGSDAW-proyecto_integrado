import React, { useEffect, useState } from 'react'
import { Link, useLocation } from 'react-router-dom'
import {
  MDBBreadcrumb,
  MDBBreadcrumbItem
} from 'mdb-react-ui-kit'
import routesNamesMap from '../helpers/routes'


const Breadcrumbs = () => {
  let location = useLocation();
  const [rawCrumbs, setRawCrumbs] = useState([]);
  const [showBreadcrumbs, setShowBreadcrumbs] = useState(true);
  let url = "";

  const prepareCrumbs = () => {
    const arrayRoutesRaw = location.pathname.split("/");
    arrayRoutesRaw.shift();
    //console.log(arrayRoutesRaw);
    for (const item of arrayRoutesRaw) {
      setRawCrumbs((oldArray) => [...oldArray, item]);
    }
  }

  const generatedCrumbs = rawCrumbs.map((crumb, index) => {
    if (index === 0) {
      url += crumb;
    } else {
      url += `/${crumb}`;
    }
    let content = <Link to={url}>{routesNamesMap.get(crumb)}</Link>;
    if (index === rawCrumbs.length - 1) {
      let prettyCrumb = routesNamesMap.get(crumb);
      if (prettyCrumb) {
        content = prettyCrumb;
      } else {
        //content = <Link to={url}>{crumb}</Link>;
        content = crumb;
      }
    }
    return <MDBBreadcrumbItem key={index}>
      {content}
    </MDBBreadcrumbItem>
  })

  useEffect(() => {
    setRawCrumbs([]);
    
    if (location.pathname === "/") {
      setShowBreadcrumbs(false);
    } else {
      setShowBreadcrumbs(true);
      prepareCrumbs();
    }
  }, [location]);


  return (
    showBreadcrumbs ? <MDBBreadcrumb aria-label="breadcrumb">Breadcrumb:{'\u00A0'}{generatedCrumbs}</MDBBreadcrumb> : null
  )
}

export default Breadcrumbs