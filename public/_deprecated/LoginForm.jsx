import React, { useEffect, useState } from 'react';
import {
  MDBInput,
  MDBCol,
  MDBRow,
  MDBCheckbox,
  MDBBtn,
  MDBContainer,
  MDBValidation,
  MDBValidationItem,
} from 'mdb-react-ui-kit';
import { Link } from 'react-router-dom';

const LoginForm = () => {
  const [formValues, setFormValues] = useState({
    formEmail: "",
    formPassword: "",
  });

  const onFormSubmit = (e) => {
    e.preventDefault();
    /* if (!e.target.checkValidity()) {
      e.stopPropagation();
    }
    e.target.classList.add('was-validated'); */
    //e.target.checkValidity();
    console.log("submit");
  };

  const handleInvalid = (e) => {
    const target = e.target;
    console.log(target);
    if (target.value.length === 0) {
      target.setCustomValidity("Campo requerido");
      console.log("requerido");
    } else {
      console.log("invalid");
    }
  };

  const handleChange = (e) => {
    //e.target.setCustomValidity('');
    setFormValues({ ...formValues, [e.target.name]: e.target.value });
  };

  return (
    <>
    <MDBValidation onSubmit={onFormSubmit}>
      <MDBValidationItem className='mb-5' /* onInvalid={handleInvalid} */>
        <MDBInput
          type='email'
          id='loginFormEmail'
          name='formEmail'
          label='Email'
          /* required */
          onChange={handleChange}
          value={formValues.formEmail}
          >
          <div className="valid-feedback">
            Valor válido
          </div>
          <div className="invalid-feedback">
            Valor inválido
          </div>
        </MDBInput>

      </MDBValidationItem>
      <MDBValidationItem className='mb-5' /* onInvalid={handleInvalid} */>
        <MDBInput
          type='password'
          id='loginFormPassword'
          name='formPassword'
          label='Contraseña'
          required
          onChange={handleChange}
          value={formValues.formPassword} /* value={formValues.formPassword} */
          >
          <div className="invalid-feedback">
            Valor inválido
          </div>
        </MDBInput>
      </MDBValidationItem>

      {/* <MDBRow className='mb-4'>
        <MDBCol className='d-flex justify-content-center'>
        <MDBCheckbox id='form2Example3' label='Remember me' defaultChecked />
        </MDBCol>
        <MDBCol>
        <a href='#!'>Forgot password?</a>
        </MDBCol>
      </MDBRow> */}

      <MDBBtn type='submit' className='mb-4' block>
        Iniciar sesión
      </MDBBtn>

      <div className='text-center'>
        <p>
          ¿No tienes cuenta? <Link to="/signup">Crear cuenta</Link>
        </p>
      </div>
    </MDBValidation>
      </>
  )
}

export default LoginForm