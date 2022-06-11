import React from 'react';
import { Outlet } from "react-router-dom";
import Navigation from '../components/Navigation';
import { MDBContainer, MDBBtn } from 'mdb-react-ui-kit';
import CookieConsent from 'react-cookie-consent';
import Footer from '../components/Footer';

const Layout = ({ showBreadcrumbs }) => {
  return (
    <>
      <header>
        <Navigation showBreadcrumbs={showBreadcrumbs} />
      </header>
      <main>
        <MDBContainer breakpoint="xl">
          <Outlet />
        </MDBContainer>
      </main>
      <Footer />
      <CookieConsent
        containerClasses='alert alert-primary d-flex align-items-center justify-content-center fixed-bottom mb-0'
        buttonText={
          <MDBBtn
            tag='div'
          >
            Acepto
          </MDBBtn>}
        type="submit"
        disableStyles
        contentClasses='me-3'
        debug={false}
      >
        Este sitio usa cookies y tal.
      </CookieConsent>
    </>
  )
}

export default Layout