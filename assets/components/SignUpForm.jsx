import React from 'react';
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

const validationSchema = yup.object({
  email: yup
    .string('Introduce email')
    .email('Introduce un email válido')
    .required('Es necesario introducir un email'),
  password1: yup
    .string('Introduce contraseña')
    .min(8, 'La contraseña debe tener al menos 8 caracteres')
    .required('Es necesario introducir la contraseña')
    /* .oneOf([yup.ref('password2'), null], 'Passwords must match') */,
  password2: yup
    .string('Introduce contraseña')
    .min(8, 'La contraseña debe tener al menos 8 caracteres')
    .required('Debe repetir la contraseña')
    .oneOf([yup.ref('password1'), null], 'Passwords must match')
    /* .test('is-same-as-password1', 'Las contraseñas no concuerdan', value => value === password1) */,
  terms: yup
    .boolean()
    .oneOf([true], 'Debe aceptar los términos y condiciones'),
});

const SigUpForm = () => {
  const formik = useFormik({
    initialValues: {
      email: '',
      password1: '',
      password2: '',
      terms: false,
    },
    validationSchema: validationSchema,
    onSubmit: (values, actions) => {
      console.log(values);
      actions.resetForm();
    },
  });

  return (
    <MDBValidation onSubmit={formik.handleSubmit} className='row g-4'>
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
      <MDBValidationItem feedback='test' invalid>
        <MDBCheckbox
          wrapperClass='d-flex justify-content-center'
          name="terms"
          label='Acepto los términos y condiciones'
          className={formik.touched.terms && formik.errors.terms ? 'is-invalid' : formik.touched.terms && !formik.errors.terms ? 'is-valid' : ''}
          value={formik.values.terms}
          onChange={formik.handleChange}  
          required       
        />
        {/* <div className="custom-valid-feedback valid-feedback">Parece correcto</div>
        {formik.errors.terms ? <div className='invalid-feedback'>{formik.errors.terms}</div> : null} */}
      </MDBValidationItem>




      <MDBBtn type='submit' className='mb-4' block>
        Crear cuenta
      </MDBBtn>

      <div className='text-center'>
        <p>
          ¿Ya tienes cuenta? <Link to="/login">Iniciar sesión</Link>
        </p>
      </div>
    </MDBValidation>
  )
}

export default SigUpForm