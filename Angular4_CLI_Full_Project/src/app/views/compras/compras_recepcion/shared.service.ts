import { EventEmitter, Injectable } from '@angular/core';

@Injectable()
export class SharedService {
    cartData = new EventEmitter<any>();
} 
