import React, { useState } from 'react';
import { Route, Routes } from 'react-router-dom';
import AdminLayout from './routes/AdminLayout';
import Documentation from './routes/Documentation';
import Home from './routes/Home';
import Layout from './routes/Layout';
import Login from './routes/Login';
import NotFound from './routes/NotFound';
import SignUp from './routes/SignUp';

const App = () => {
  return (
    <Routes>
      <Route path="/" element={<Layout showBreadcrumbs={true} />}>
        <Route index element={<Home />} />
        <Route path="docs" element={<Documentation />} />
        <Route path="login" element={<Login />} />
        <Route path="signup" element={<SignUp />} />
        <Route path="login/login/login" element={<Login />} />
        <Route path="login/:loginId" element={<Login />} />
      </Route>
      <Route path="admin" element={<AdminLayout />} >

      </Route>
      <Route path="*" element={<Layout showBreadcrumbs={false} />} >
        <Route path="*" element={<NotFound />} />
      </Route>
    </Routes>
  )
}

export default App