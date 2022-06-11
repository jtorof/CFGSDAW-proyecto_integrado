import React, { useEffect, useState } from 'react';
import { Route, Routes, Navigate } from 'react-router-dom';
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
import AdvancedOptions from './routes/AdvancedOptions';
import RequireNotAuth from './components/RequireNotAuth';
import Credits from './routes/Credits';

const App = () => {
  const [globalUser, setGlobalUser] = useState({});
  const [globalUserInfo, setGlobalUserInfo] = useState({});

  useEffect(() => {
    if (window.user) {
      // console.log(window.user);
      // console.log(window.userInfo);
      setGlobalUser(window.user);
      setGlobalUserInfo(window.userInfo);
    }
  }, []);


  return (
    <UserContext.Provider value={{ globalUser, setGlobalUser, globalUserInfo, setGlobalUserInfo }}>
      <HeaderTagsSetter />
      <Routes>
        <Route path="/" element={<Layout showBreadcrumbs={true} />}>
          <Route index element={<Home />} />
          <Route path="docs" element={<Documentation />} />
          <Route
            path="login"
            element={
              <RequireNotAuth>
                <Login />
              </RequireNotAuth>
            }
          />
          <Route
            path="registro"
            element={
              <RequireNotAuth>
                <SignUp />
              </RequireNotAuth>
            }
          />
          <Route path="perfil" >
            <Route
              index
              element={
                <RequireAuth>
                  <Profile />
                </RequireAuth>
              }
            />
            <Route
              path="opciones-avanzadas"
              element={
                <RequireAuth>
                  <AdvancedOptions />
                </RequireAuth>
              }
            />
          </Route>
          <Route path="creditos" element={<Credits />} />
        </Route>
        {/* <Route path="admin" element={<AdminLayout />} >
        </Route> */}
        <Route path="*" element={<Layout showBreadcrumbs={false} />} >
          <Route path="acceso-restringido" element={<AccessRestricted />} />
          <Route path="*" element={<NotFound />} />
        </Route>
      </Routes>
    </UserContext.Provider>
  )
}

export default App