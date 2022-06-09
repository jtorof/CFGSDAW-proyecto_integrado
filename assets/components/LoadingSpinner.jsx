import React from 'react'
import { MDBSpinner } from 'mdb-react-ui-kit';

const LoadingSpinner = ({color, classes}) => {
  return (
    <MDBSpinner  color={color} className={classes} role='status'>
      <span className='visually-hidden'>Cargando...</span>
    </MDBSpinner>
  )
}

export default LoadingSpinner