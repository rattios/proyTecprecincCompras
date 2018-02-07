import { Injectable } from '@angular/core';

@Injectable()
export class RutaService {

  //public ruta_servidor="http://localhost:8000/";
  //public ruta_servidor="http://localhost/proyTecprecincCompras/tecprecincComprasAPI/public/"; //Local stalin
  public ruta_servidor="http://vivomedia.com.ar/Tecprecinc/tecprecincComprasAPI/public/"; //Servidor de Rafael

  constructor() { }

  get_ruta(){
  	return this.ruta_servidor;
  }

}
