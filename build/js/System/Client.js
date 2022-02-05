import Axios from 'axios';

const client = () => {
    const defaultOptions = {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'x-frontloader': 'true',
        },
    };

    return Axios.create(defaultOptions);
}

export default client();
