import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useFormik } from 'formik';
import * as yup from 'yup';
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
import fetchData from '../helpers/fetchData';
import { Alert } from 'react-bootstrap';

const validationSchema = yup.object({
  email: yup
    .string('Introduce email')
    .email('Introduce un email válido')
    .required('Es necesario introducir un email'),
  password1: yup
    .string('Introduce contraseña')
    .min(8, 'La contraseña debe tener al menos 8 caracteres')
    .required('Es necesario introducir la contraseña'),
  password2: yup
    .string('Introduce contraseña')
    .min(8, 'La contraseña debe tener al menos 8 caracteres')
    .required('Debe repetir la contraseña')
    .oneOf([yup.ref('password1'), null], 'Las contraseñas deben coincidir'),
  terms: yup
    .boolean()
    .oneOf([true], 'Debe aceptar los términos y condiciones'),
});

const SigUpForm = () => {
  const navigate = useNavigate();
  const [variant, setVariant] = useState("danger");

  const formik = useFormik({
    initialValues: {
      email: '',
      password1: '',
      password2: '',
      terms: false,
    },
    validationSchema: validationSchema,
    onSubmit: async (values, actions) => {
      //console.log(values);
      try {
        actions.setStatus(undefined);
        const data = await fetchData('/register', 'POST', {}, values);
        console.log(data);
        if (data.message === "Account successfully created") {
          setVariant("success");
          actions.resetForm({
            status: {
              message: "Cuenta creada con éxito"
            }
          });
          setTimeout(() => {
            navigate('/login')
          }, 2000);
          return;
        }

        if (data.message !== 'Account successfully created') {
          setVariant("danger");
          actions.setSubmitting(false);
          actions.resetForm({
            status: {
              message: "Ha habido un error"
            }
          });
          return;
        }

      } catch (error) {
        console.log(error);
      }
    },
  });

  return (
    <>
      <MDBRow center>
        <MDBCol md='6'>
          <h2>Formulario de Registro</h2>
          {formik.status && formik.status.message ?
            <Alert variant={variant}>{formik.status.message}</Alert>
            : null}
          <form onSubmit={formik.handleSubmit} className='row g-4'>
            <MDBValidationItem feedback={formik.errors.email} invalid>
              <MDBInput
                name="email"
                label="Email"
                type="email"
                className={formik.touched.email && formik.errors.email ? 'is-invalid' : formik.touched.email && !formik.errors.email ? 'is-valid' : ''}
                value={formik.values.email}
                onChange={formik.handleChange}
              >
                <div className="custom-valid-feedback valid-feedback">Parece correcto</div>
              </MDBInput>
            </MDBValidationItem>
            <MDBValidationItem feedback={formik.errors.password1} invalid>
              <MDBInput
                name="password1"
                label="Contraseña"
                type="password"
                className={formik.touched.password1 && formik.errors.password1 ? 'is-invalid' : formik.touched.password1 && !formik.errors.password1 ? 'is-valid' : ''}
                value={formik.values.password1}
                onChange={formik.handleChange}
              >
                <div className="custom-valid-feedback valid-feedback">Parece correcto</div>
              </MDBInput>
            </MDBValidationItem>
            <MDBValidationItem feedback={formik.errors.password2} invalid>
              <MDBInput
                name="password2"
                label="Repita la contraseña"
                type="password"
                className={formik.touched.password2 && formik.errors.password2 ? 'is-invalid' : formik.touched.password2 && !formik.errors.password2 ? 'is-valid' : ''}
                value={formik.values.password2}
                onChange={formik.handleChange}
              >
                <div className="custom-valid-feedback valid-feedback">Parece correcto</div>
              </MDBInput>
            </MDBValidationItem>
            <MDBValidationItem feedback={formik.errors.terms} invalid className='d-flex justify-content-center'>
              <MDBCheckbox
                // wrapperClass='d-flex justify-content-center'
                name="terms"
                label='Acepto los términos y condiciones'
                className={formik.touched.terms && formik.errors.terms ? 'is-invalid' : formik.touched.terms && !formik.errors.terms ? 'is-valid' : ''}
                value={formik.values.terms}
                onChange={formik.handleChange}
              />
            </MDBValidationItem>
            <div className="d-block text-center">
              <MDBBtn type='submit' disabled={formik.isSubmitting}>
                Crear cuenta
              </MDBBtn>
            </div>
            <div className='text-center'>
              <p>
                ¿Ya tienes cuenta? <Link to="/login">Iniciar sesión</Link>
              </p>
            </div>
          </form>
        </MDBCol>
      </MDBRow>
    </>
  )
}

export default SigUpForm