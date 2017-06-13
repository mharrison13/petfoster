import {Component, OnInit} from "@angular/core";
import {ActivatedRoute, Params} from "@angular/router";
import {Observable} from "rxjs";
import {MessageService} from "../services/message.service";
import {Message} from "../classes/message";
import {Status} from "../classes/status";

@Component({
	templateUrl: "./templates/message.php"
})

export class MessageComponent {

	newMessage : Message = new Message(null, null, null, null, null, null);
	messages : Message[] = [];
	status : Status = null;

	constructor(private messageService : MessageService) {}

	ngOnInit() : void {
		this.getAllMessages();
	}

	createMessage() : void {
		this.messageService.createMessage(this.newMessage)
			.subscribe(status => this.status = status);
	}

	getAllMessages() : void {
		this.messageService.getAllMessages()
			.subscribe(messages => this.messages = messages);
	}

}