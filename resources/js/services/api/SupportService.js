import ApiService from "./ApiService.js";
import {authService} from "./AuthService.js";

class HistoryService extends ApiService {

    getBaseHeaders() {
        const authHeader = authService.getAuthHeader();
        return {...authHeader};
    }

    async getTickets() {
        return await super.get( '/support/tickets', this.getBaseHeaders());
    }

    async getTicketById(ticketId) {
        return await super.get(
            `/support/tickets/${ticketId}`,
            this.getBaseHeaders()
        );
    }

    async createTicket(subject, message, image) {
        this.update({
            subject: subject,
            message: message,
            image: image
        });
        return await super.post(
            `/support/tickets`,
            this.getBaseHeaders()
        );
    }

    async getMessages(ticketId) {
        return await super.get(
            `/support/tickets/${ticketId}/messages`,
            this.getBaseHeaders()
        );
    }

    async sendMessage(ticketId, message) {
        this.update({
            message: message
        });
        return await super.post(
            `/support/tickets/${ticketId}/messages`,
            this.getBaseHeaders()
        );
    }
}

export default HistoryService
