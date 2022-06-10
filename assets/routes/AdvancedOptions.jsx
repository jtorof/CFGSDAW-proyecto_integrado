import React, { useContext, useState } from 'react';
import { Link } from 'react-router-dom';
import {
  MDBRow,
  MDBBtn,
  MDBIcon,
  MDBModal,
  MDBModalDialog,
  MDBModalContent,
  MDBModalHeader,
  MDBModalTitle,
  MDBModalBody,
  MDBModalFooter,
} from 'mdb-react-ui-kit';
import fetchData from '../helpers/fetchData';
import { UserContext } from '../helpers/context';
import LoadingSpinner from '../components/LoadingSpinner';

const AdvancedOptions = () => {
  const context = useContext(UserContext);
  const [showModal, setShowModal] = useState(false);
  const [loading, setLoading] = useState(false);

  const resetApiContent = async () => {
    try {
      const data = await fetchData('/user/operations/reset-data', 'POST');
      if (("message" in data) && data.message === "Datos regenerados") {
        setLoading(false);
        setShowModal(false);
      }
    } catch (error) {
      console.log(error);
    }
  }

  const handleShowModal = () => {
    setShowModal(!showModal)
  }

  const handleResetData = () => {
    if (loading) return;    
    setLoading(true);
    resetApiContent();
  }

  return (
    <>
      <MDBRow>
        <h2 className='d-flex justify-content-between' >
          Opciones Avanzadas - {context.globalUser?.email}
          <MDBBtn outline tag={Link} to='../' >
            <MDBIcon fas icon="chevron-left" /> Volver a perfil
          </MDBBtn>
        </h2>
      </MDBRow>
      <MDBRow>
        <p className='d-flex justify-content-between align-items-center'>Resetear datos: se borran los datos actuales y se reemplazan por una nueva copia de los datos maestros. Las estadísticas permanecen.
          <MDBBtn outline color='danger' onClick={handleShowModal}>
            <MDBIcon fas icon="exclamation-triangle" /> Resetear Datos
          </MDBBtn>
        </p>
      </MDBRow>
      <MDBModal show={showModal} setShow={setShowModal} tabIndex='-1'>
        <MDBModalDialog className='flex-column justify-content-center' centered>
          <MDBModalContent>
            <MDBModalHeader className='bg-danger text-white'>
              <MDBModalTitle>Resetear Datos</MDBModalTitle>
              <MDBBtn className='btn-close' color='none' onClick={handleShowModal}></MDBBtn>
            </MDBModalHeader>
            <MDBModalBody>Estos cambios son irreversibles</MDBModalBody>
            <MDBModalFooter>
              <MDBBtn outline color='info' onClick={handleShowModal}>
                Cancelar
              </MDBBtn>
              <MDBBtn color='danger' onClick={handleResetData}>
                <MDBIcon fas icon="exclamation-triangle" /> Resetear Datos
              </MDBBtn>
            </MDBModalFooter>
          </MDBModalContent>
          {loading ? <LoadingSpinner color='dark' classes='mt-3' /> : null}
        </MDBModalDialog>
      </MDBModal>
    </>
  )
}

export default AdvancedOptions