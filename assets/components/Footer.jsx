import React from 'react';
import { Link } from 'react-router-dom';
import {
  MDBFooter,
  MDBListGroup,
  MDBListGroupItem,
  MDBContainer,
} from 'mdb-react-ui-kit';

const Footer = () => {
  return (
    <MDBFooter className='text-center p-3' color='white' bgColor='dark'>
      <MDBListGroup flush >
        <MDBListGroupItem>APIParaPracticar - Jesús Toro 2022</MDBListGroupItem>
        <MDBListGroupItem><Link to='creditos'>Créditos</Link></MDBListGroupItem>
      </MDBListGroup>
    </MDBFooter>
  )
}

export default Footer