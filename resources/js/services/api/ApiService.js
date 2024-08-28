import Form from 'vform';

class ApiService extends Form {
    constructor(data = {}) {
        super(data);
    }

    #_token = '';
    #_withAuth = false;

    getApiPrefix() {
        return '/api'
    }

    async get(url, headers) {
        const params = {};
        if (headers) params.headers = headers;

        return await super.get(this.getApiPrefix() + url, params);
    }

    async post(url, headers) {
        const params = {};
        if (headers) params.headers = headers;

        return await super.post(this.getApiPrefix() + url, params);
    }

    async delete(url, headers) {
        const params = {};
        if (headers) params.headers = headers;

        return await super.delete(this.getApiPrefix() + url, params);
    }
}

export default ApiService;
