import React from 'react';
import {
  MDBTable,
  MDBTableBody,
} from 'mdb-react-ui-kit';
import { CopyBlock, nord } from "react-code-blocks";

const collectionGetExample =
  `fetch('https://apiparapracticar.ddns.net/api/users', {
    method: 'GET',
    headers: {
        'X-AUTH-TOKEN': 'añade aquí tu API key',
    },
})
.then(response => response.json())
.then(json => console.log(json))`;

const itemGetExample =
  `const fetchData =async () => {
    const response = await fetch('https://apiparapracticar.ddns.net/api/user/1', {
        method: 'GET',
        headers: {
            'X-AUTH-TOKEN': 'añade aquí tu API key',
        },
    })
    const data = await response.json();
    console.log(data);
};
fetchData();`;

const Documentation = () => {
  return (
    <>
      <section className='row'>
        <h2>Registro y Configuración</h2>
        <p>APIParaPracticar es una API REST gratuita que requiere que el usuario se registre para tener acceso a los recursos y operaciones que ofrece.</p>
        <p>Una vez se ha completado el registro, el usuario debe generar su copia de los recursos de la API, y también una API Key, una clave que, al enviarse en una cabecera en cada petición, identifica al usuario y le permite realizar operaciones.</p>
        <p>La cabecera que incluya la API Key debe ser obligatoriamente "X-AUTH-TOKEN". Puede ver ejemplos de uso en secciones posteriores de la documentación.</p>
      </section>
      <section className='row'>
        <h2>Recursos</h2>
        <MDBTable borderless>
          <MDBTableBody>
            <tr>
              <td>/api/users</td>
              <td>4 Usuarios base</td>
              <td>Se pueden añadir más hasta un total de 15</td>
            </tr>
          </MDBTableBody>
        </MDBTable>
        <p>Por el momento solo se ofrece un único recurso, "users" que a su vez contiene relaciones con "address" y "phone".</p>
      </section>
      <section className='row'>
        <h2>Operaciones y Rutas</h2>
        <p>Se pueden utilizar todas las operaciones habituales de las API REST*</p>
        <MDBTable>
          <MDBTableBody>
            <tr>
              <td>GET</td>
              <td>/api/users</td>
            </tr>
            <tr>
              <td>POST</td>
              <td>/api/users</td>
            </tr>
            <tr>
              <td>GET</td>
              <td>/api/users/&#123;id&#125;</td>
            </tr>
            <tr>
              <td>PUT</td>
              <td>/api/users/&#123;id&#125;</td>
            </tr>
            <tr>
              <td>PATCH</td>
              <td>/api/users/&#123;id&#125;</td>
            </tr>
            <tr>
              <td>DELETE</td>
              <td>/api/users/&#123;id&#125;</td>
            </tr>
          </MDBTableBody>
        </MDBTable>
        <p><strong>*</strong>Este proyecto utiliza <a href='https://api-platform.com/' target={'_blank'}>API Platform</a>
          , y su implementación del método PUT se comporta de forma muy similar a PATCH. Puedes ver más información en la siguiente sección.</p>
      </section>
      <section className='row' id='operacion-put'>
        <h2>Operación PUT</h2>
        <p>La implementación que realiza <a href='https://api-platform.com/' target={'_blank'}>API Platform</a>, que este proyecto utiliza, de la operación PUT, no se ajusta al comportamiento normal.</p>
        <p>Lo esperable es que se sobreescriba la entidad por completo, pero en este caso las propiedades no incluídas en los datos enviados no se eliminan. Para que se eliminen, se tienen que incluir y el valor debe ser puesto a <code>null</code>.</p>
        <p>En un futuro el comportamiento se ajustará al estándar. Para más información puedes consultar la <a href='https://api-platform.com/docs/core/operations/#operations' target={'_blank'}>documentación oficial</a>.</p>
      </section>
      <section className='row'>
        <h2>Ejemplos</h2>
        <h3>GET /api/users</h3>
        <CopyBlock
          text={collectionGetExample}
          showLineNumbers={true}
          wrapLongLines={true}
          codeBlock={true}
          language={"javascript"}
          theme={nord}
        />
        <h3 className='mt-3'>GET /api/user/1</h3>
        <CopyBlock
          text={itemGetExample}
          showLineNumbers={true}
          wrapLongLines={true}
          codeBlock={true}
          language={"javascript"}
          theme={nord}
        />
      </section>
    </>
  )
}

export default Documentation