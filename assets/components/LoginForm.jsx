import React, { useContext } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useFormik } from 'formik';
import * as yup from 'yup';
import {
  MDBInput,
  MDBCol,
  MDBRow,
  MDBBtn,
  MDBValidationItem,
} from 'mdb-react-ui-kit';
import Alert from 'react-bootstrap/Alert';
import fetchData from '../helpers/fetchData';
import { UserContext } from '../helpers/context';

const validationSchema = yup.object({
  email: yup
    .string('Introduce email')
    .email('Introduce un email válido')
    .required('Es necesario introducir un email'),
  password: yup
    .string('Introduce contraseña')
    .min(8, 'La contraseña debe tener al menos 8 caracteres')
    .required('Es necesario introducir una contraseña'),
});

const loginHeaders = {
  'Content-Type': 'application/json',
}

const customValidFeedback = `Parece correcto`;

const LoginForm = ({ prevLocation }) => {
  const context = useContext(UserContext);
  const navigate = useNavigate();

  const formik = useFormik({
    initialValues: {
      email: '',
      password: '',
    },
    validationSchema: validationSchema,
    onSubmit: async (values, actions) => {
      const loginData = {
        username: values.email,
        password: values.password
      }
      try {
        actions.setStatus(undefined);
        const data = await fetchData('/login', 'POST', loginHeaders, loginData);
        console.log(data);
        if ("error" in data) {
          actions.setSubmitting(false);
          actions.resetForm({
            status: {
              message: "Credenciales inválidas"
            }
          });
          return;
        }
        context.setGlobalUser(JSON.parse(data.user));
        context.setGlobalUserInfo(data.userInfo);
        if (typeof prevLocation !== "undefined") {
          navigate(prevLocation, { replace: true });
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
          <h2>Formulario de Inicio de Sesión</h2>

          {formik.status && formik.status.message ?
            <Alert variant='warning'>{formik.status.message}</Alert>
            : null}

          <form onSubmit={formik.handleSubmit} className='row g-4' noValidate>
            <MDBValidationItem feedback={formik.errors.email} invalid>
              <MDBInput
                name="email"
                label="Email"
                type="email"
                className={formik.touched.email && formik.errors.email ? 'is-invalid' : formik.touched.email && !formik.errors.email ? 'is-valid' : ''}
                value={formik.values.email}
                onChange={formik.handleChange}
              >
                <div className="custom-valid-feedback valid-feedback">{customValidFeedback}</div>
                {/* {formik.errors.email ? <div className='invalid-feedback'>{formik.errors.email}</div> : null} */}
              </MDBInput>
            </MDBValidationItem>
            <MDBValidationItem feedback={formik.errors.password} invalid>
              <MDBInput
                name="password"
                label="Contraseña"
                type="password"
                className={formik.touched.password && formik.errors.password ? 'is-invalid' : formik.touched.password && !formik.errors.password ? 'is-valid' : ''}
                value={formik.values.password}
                onChange={formik.handleChange}
              >
                <div className="custom-valid-feedback valid-feedback">{customValidFeedback}</div>
                {/*  {formik.errors.password ? <div className='invalid-feedback'>{formik.errors.password}</div> : null} */}
              </MDBInput>
            </MDBValidationItem>
            <div className="d-block text-center">
              <MDBBtn type='submit' disabled={formik.isSubmitting}>
                Iniciar sesión
              </MDBBtn>
            </div>
            <div className='text-center'>
              <p>
                ¿No tienes cuenta? <Link to="/registro">Crear cuenta</Link>
              </p>
            </div>
          </form>
        </MDBCol>
      </MDBRow>
    </>
  );
};

export default LoginForm