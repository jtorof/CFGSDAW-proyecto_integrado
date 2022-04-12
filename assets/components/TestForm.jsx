import React, { useState } from 'react';
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

const TestForm = () => {
  const formik = useFormik({
    initialValues: {
      email: '',
      password: '',
    },
    validationSchema: validationSchema,
    onSubmit: (values, actions) => {
      console.log(values);
      actions.resetForm();
    },
  });

  return (
    <div>
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
            <div className="custom-valid-feedback valid-feedback">Parece correcto</div>
           {/*  {formik.errors.password ? <div className='invalid-feedback'>{formik.errors.password}</div> : null} */}
          </MDBInput>
        </MDBValidationItem>
        <div className="d-block">
          <MDBBtn type='submit'>
            Inicir sesión
          </MDBBtn>
        </div>
      </MDBValidation>
    </div>
  );
};

export default TestForm