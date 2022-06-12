import React from 'react';
import { Link } from 'react-router-dom';
import {
  MDBRow,
} from 'mdb-react-ui-kit';

const NotFound = () => {
  return (
    <picture className='d-flex flex-grow-1 justify-content-center align-items-center'>
      <source media='(max-width: 1920px)' srcSet='/img/404.jpg' />
      <source media='(min-width: 1921px)' srcSet='/img/404.svg' />
      <img src='/img/404.jpg' alt='Imagen de código 404' id='not-found-image'/>
    </picture>
  )
}

export default NotFound