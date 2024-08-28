import ApiService from "./ApiService.js";
import {authService} from "./AuthService.js";

class HistoryService extends ApiService {

    getBaseHeaders() {
        const authHeader = authService.getAuthHeader();
        return {...authHeader};
    }

    async getCategories() {
        return await super.get( '/news/categories', this.getBaseHeaders());
    }

    async getArticles(categoryId, page = 1) {
        return await super.get(
            `/news/categories/${categoryId}/articles?page=${page}`,
            this.getBaseHeaders()
        );
    }

    async getArticle(articleId) {
        return await super.get(
            `/news/articles/${articleId}`,
            this.getBaseHeaders()
        );
    }
}

export default HistoryService
