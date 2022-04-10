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
        content = <Link to={url}>{crumb}</Link>;
      }
    }
    return <MDBBreadcrumbItem key={index}>
      {content}
    </MDBBreadcrumbItem>
  })

  useEffect(() => {
    setRawCrumbs([]);
    prepareCrumbs();
  }, [location])


  return (
    <MDBBreadcrumb>
      {generatedCrumbs}
    </MDBBreadcrumb>
  )
}

export default Breadcrumbs