import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
  stages: [
    { duration: '1m', target: 8 }, 
  ],
};

export default function () {
  const payload = {
    apelido: `josé_${__VU}_${__ITER}_` + Math.random(),
    nome: 'José Roberto',
    nascimento: new Date().toISOString().split('T')[0],
    stack: ['C#', 'Node', 'Oracle'],
  };

  const params = {
    headers: {
      'Content-Type': 'application/json',
    },
  };

  let response = http.post('http://192.168.92.136:9999/pessoas', JSON.stringify(payload), params);

  check(response, {
    'is status 201 or 422': (r) => r.status === 201 || r.status === 422,
  });
}