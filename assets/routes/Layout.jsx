import React from 'react';
import { Outlet } from "react-router-dom";
import Navigation from '../components/Navigation';
import { MDBContainer } from 'mdb-react-ui-kit';

const Layout = ({ showBreadcrumbs }) => {
  return (
    <>
      <header>
        <Navigation showBreadcrumbs={showBreadcrumbs} />
      </header>
      <main>
        <Outlet />
      </main>
      <footer>FOOTER</footer>
    </>
  )
}

export default Layout