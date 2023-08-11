import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
  stages: [
    { duration: '1m', target: 5 }, 
  ],
};

export default function () {
  const params = {
    headers: {
      'Content-Type': 'application/json',
    },
  };

  let response = http.get('http://192.168.1.4:9999/pessoas/?t=jo', params);

  check(response, {
    'is status 200 or 404': (r) => r.status === 201 || r.status === 404,
  });
}