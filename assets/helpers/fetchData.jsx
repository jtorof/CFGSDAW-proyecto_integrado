const fetchData = async (resource = '', method = 'GET', headers = {}, data = {}) => {
  const init = {
    method: method,
  };

  if (Object.keys(headers).length !== 0) {
    init.headers = headers;
  }

  if (Object.keys(data).length !== 0) {
    init.body = JSON.stringify(data);
  }

  try {
    //console.log(init);
    const response = await fetch(resource, init); 
    const data = await response.json(); 
    return data;
  } catch (error) {
    console.log(error);
  }
}

export default fetchData