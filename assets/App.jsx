import React, { useEffect, useState } from 'react';
import { Route, Routes, Navigate } from 'react-router-dom';
import AdminLayout from './routes/AdminLayout';
import Documentation from './routes/Documentation';
import Home from './routes/Home';
import Layout from './routes/Layout';
import Login from './routes/Login';
import NotFound from './routes/NotFound';
import SignUp from './routes/SignUp';
import Profile from './routes/Profile';
import { UserContext } from './helpers/context';
import AccessRestricted from './routes/AccessRestricted';
import RequireAuth from './components/RequireAuth';
import HeaderTagsSetter from './components/HeaderTagsSetter';

const App = () => {
  const [globalUser, setGlobalUser] = useState({});

  useEffect(() => {
    if (window.user) {
      console.log(window.user);
      setGlobalUser(window.user);
    }
  }, []);


  return (
    <UserContext.Provider value={{ globalUser, setGlobalUser }}>
      <HeaderTagsSetter />
      <Routes>
        <Route path="/" element={<Layout showBreadcrumbs={true} />}>
          <Route index element={<Home />} />
          <Route path="docs" element={<Documentation />} />
          <Route path="login" element={!("email" in globalUser) ? <Login /> : <Navigate to="/profile" replace />} />
          <Route path="signup" element={!("email" in globalUser) ? <SignUp /> : <Navigate to="/profile" replace />} />
          {/* <Route path="login/login/login" element={<Login />} />
          <Route path="login/:loginId" element={<Login />} /> */}
          <Route
            path="profile"
            element={
              <RequireAuth>
                <Profile />
              </RequireAuth>
            }
          />
        </Route>
        <Route path="admin" element={<AdminLayout />} >

        </Route>
        <Route path="*" element={<Layout showBreadcrumbs={false} />} >
          <Route path="acceso-restringido" element={<AccessRestricted />} />
          <Route path="*" element={<NotFound />} />
        </Route>
      </Routes>
    </UserContext.Provider>
  )
}

export default App