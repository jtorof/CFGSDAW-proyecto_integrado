import React from 'react';
import {
  MDBCol,
  MDBRow,
  MDBTable,
  MDBTableBody,
  MDBBtn,
  MDBCollapse,
  MDBIcon,
  MDBSpinner,
  MDBContainer,
} from 'mdb-react-ui-kit';
import { Link } from 'react-router-dom';

const Home = () => {
  return (
    <>
      <section className='row m-0 p-0 justify-content-center align-items-center'>
        <picture>
          <source media='(max-aspect-ratio: 1/1)' srcSet='/img/home-image-mobile.jpg' />
          <source media='(max-width: 768px)' srcSet='/img/home-image-mobile.jpg' />
          <source media='(min-width: 769px)' srcSet='/img/home-image.jpg' />
          <img src='/img/home-image.jpg' alt='Imagen de fondo: escritorio con PC en el que se ve código' className='home-image' />
        </picture>
        <section className='col-md-6 position-absolute text-white text-center' >
          <p>APIParaPracticar es una API gratuita para practicar operaciones con APIs. Permite llevar a cabo todas las operaciones típicas de las API REST.</p>
          <MDBBtn tag={Link} to='registro'>
            Regístrate y comienza a practicar
          </MDBBtn>
        </section>
      </section>
      {/* <section>
        <p>Atacar APis es una de las habilidades que debe adquirir cualquier programador web. Con APIParaPracticar podrás familiarizarte con las distintas operaciones que se pueden llevar a cabo con una API REST.</p>
        <p></p>
      </section> */}
    </>
  )
}

export default Home