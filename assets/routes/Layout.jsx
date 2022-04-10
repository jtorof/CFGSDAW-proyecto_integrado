import React from 'react'
import { Outlet } from "react-router-dom";
import Navigation from '../components/Navigation';

const Layout = ( {showBreadcrumbs} ) => {
  return (
    <>
      <Navigation showBreadcrumbs={showBreadcrumbs}/>
      <main><Outlet /></main>
      <footer>FOOTER</footer>
    </>
  )
}

export default Layout