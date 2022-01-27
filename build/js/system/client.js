import axios from 'axios';

const client = () => {
    const defaultOptions = {
        method: 'get', headers: {
            'Content-Type': 'application/json',
            'x-frontloader':'true',
        },
    };

    return axios.create(defaultOptions);
}

export default client();