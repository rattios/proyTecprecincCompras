import { Component, OnInit, Input,Pipe, PipeTransform } from '@angular/core';
import {CommonModule, NgClass, DatePipe } from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../../services/ruta.service';
import {MatDatepicker} from '@angular/material/datepicker';
import {DateAdapter} from '@angular/material';

@Component({
  templateUrl: 'entregados.component.html'
})
export class entregadosComponent {
  public prov: any;
  public pedidos: any;
  public pedidos0: any=[];
  public pedidos1: any=[];
  public pedidos2: any=[];
  public pedidos3: any=[];
  public productos: any;
  public informacion: any;
  public proveedor: any='';
  public loading=true;
  public verInfo=false;
  public inicioD2:any=new Date();
  public finD2:any=new Date();

  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {

      var principio:any= new Date();
      var mes:any=principio.getMonth()+1; 
      var anio:any=principio.getYear()+1900;
      principio=mes+"-01-"+anio;
      principio=new Date(principio);
      this.inicioD2=principio;
      console.log(principio);
      var datePipe = new DatePipe("en-US");
      console.log(datePipe.transform(new Date(), 'dd-MM-yyyy'));

      this.http.get(this.ruta.get_ruta()+'pedidos2?inicio='+datePipe.transform(principio, 'dd-MM-yyyy')+'&fin='+datePipe.transform(new Date(), 'dd-MM-yyyy'))
           .toPromise()
           .then(
           data => {
             this.prov=data;
               this.pedidos=this.prov.pedidos;
              console.log(this.pedidos);
              for (var i = 0; i < this.pedidos.length; ++i) {
                if(this.pedidos[i].estado==0) {
                  this.pedidos0.push(this.pedidos[i]);
                }else if(this.pedidos[i].estado==1) {
                  this.pedidos1.push(this.pedidos[i]);
                }else if(this.pedidos[i].estado==2) {
                  this.pedidos2.push(this.pedidos[i]);
                }else if(this.pedidos[i].estado==3) {
                  this.pedidos3.push(this.pedidos[i]);
                }
              }
              for (var i = 0; i < this.pedidos.length; i++) {
                for (var j = 0; j < this.pedidos[i].solicitud.length; j++) {
                  for (var k = 0; k < this.prov.centrocostos.length; k++) {
                    if(this.pedidos[i].solicitud[j].pivot.centro_costos_id==this.prov.centrocostos[k].id) {
                      this.pedidos[i].solicitud[j].pivot.nombre_centro_costo=this.prov.centrocostos[k].descripcion;
                      //alert(this.pedidos[i].solicitud[j].pivot.centro_costos_id);
                    }
                  }
                }
              }
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
             alert('Ha ocurrido un error!');
           });
    }
    buscar(){
      var datePipe = new DatePipe("en-US");
      this.loading=true;
      this.http.get(this.ruta.get_ruta()+'pedidos2?inicio='+datePipe.transform(this.inicioD2, 'dd-MM-yyyy')+'&fin='+datePipe.transform(new Date(), 'dd-MM-yyyy'))
           .toPromise()
           .then(
           data => {
             this.prov=data;
               this.pedidos=this.prov.pedidos;
              console.log(this.pedidos);
              for (var i = 0; i < this.pedidos.length; ++i) {
                if(this.pedidos[i].estado==0) {
                  this.pedidos0.push(this.pedidos[i]);
                }else if(this.pedidos[i].estado==1) {
                  this.pedidos1.push(this.pedidos[i]);
                }else if(this.pedidos[i].estado==2) {
                  this.pedidos2.push(this.pedidos[i]);
                }else if(this.pedidos[i].estado==3) {
                  this.pedidos3.push(this.pedidos[i]);
                }
              }
              for (var i = 0; i < this.pedidos.length; i++) {
                for (var j = 0; j < this.pedidos[i].solicitud.length; j++) {
                  for (var k = 0; k < this.prov.centrocostos.length; k++) {
                    if(this.pedidos[i].solicitud[j].pivot.centro_costos_id==this.prov.centrocostos[k].id) {
                      this.pedidos[i].solicitud[j].pivot.nombre_centro_costo=this.prov.centrocostos[k].descripcion;
                      //alert(this.pedidos[i].solicitud[j].pivot.centro_costos_id);
                    }
                  }
                }
              }
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
             alert('Ha ocurrido un error!');
           });
    }

    reset(){
      this.loading=true;
      this.pedidos0=[];
      this.pedidos1=[];
      this.pedidos2=[];
      this.pedidos3=[];
      this.http.get(this.ruta.get_ruta()+'pedidos')
           .toPromise()
           .then(
           data => {
             this.prov=data;
               this.pedidos=this.prov.pedidos;
              console.log(this.pedidos);
              for (var i = 0; i < this.pedidos.length; ++i) {
                if(this.pedidos[i].estado==0) {
                  this.pedidos0.push(this.pedidos[i]);
                }else if(this.pedidos[i].estado==1) {
                  this.pedidos1.push(this.pedidos[i]);
                }else if(this.pedidos[i].estado==2) {
                  this.pedidos2.push(this.pedidos[i]);
                }else if(this.pedidos[i].estado==3) {
                  this.pedidos3.push(this.pedidos[i]);
                }
              }
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
             alert('Ha ocurrido un error!');
           });
    }

    
}
