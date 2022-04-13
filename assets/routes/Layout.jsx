import React from 'react';
import { Outlet } from "react-router-dom";
import Navigation from '../components/Navigation';
import { MDBContainer, MDBBtn } from 'mdb-react-ui-kit';
import CookieConsent from 'react-cookie-consent';

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
      <CookieConsent
        containerClasses='alert alert-primary d-flex align-items-center justify-content-between'
        buttonText={<MDBBtn
          tag='div'
        >
          Acepto
        </MDBBtn>}
        type="submit"
        disableStyles
        debug={true}
      >
        This website uses cookies to enhance the user experience.
      </CookieConsent>
    </>
  )
}

export default Layout