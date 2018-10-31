import { Component, OnInit, Input } from '@angular/core';
import {CommonModule, NgClass} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../services/ruta.service';
import { SharedService } from './shared.service';

@Component({
  selector: 'app-picking',
  templateUrl: 'picking.component.html'
})
export class pickingComponent {
  public prov: any;
  public pedidos: any;
  public productos: any;
  public proveedor: any='';
  public cantEntregar1=0;
  public cantEntregar2=0;
  public cantidadSolicitada=0;
  public showP1=true;
  public showP2=true;
  public showP11=true;
  public showP22=true;
  public loading=true;
  public departamentos:any;
  public empleados:any;
  public empleado_id:any;
  private _name: any;
  public aEntregar:any;


  @Input() producto:any;
  @Input() informacion:any;

  constructor(private http: HttpClient, private ruta: RutaService,private sharedService: SharedService) {
    this.sharedService.cartData.subscribe(
          (data: any) => {
            console.log(data);
            this.showP1=true;
            this.showP2=true;
            setTimeout(()=>{
              this.cantidadSolicitada=this.producto.pivot.cantidad;
              this.cantEntregar1=this.producto.pivot.cantidad;
              console.log(data);
            },1000);
            
            //alert('chato');
          });
  }


   ngOnInit(): void {
       this.loading=false;
      console.log(this.producto);
      this.cantidadSolicitada=this.producto.pivot.cantidad;
      this.cantEntregar1=this.producto.pivot.cantidad;
      this.producto.bstock=true;
      this.producto.bstock2=true;
      this.producto.observacion='';
      
      this.http.get(this.ruta.get_ruta()+'usuarios?rol='+10+"&departamento_id="+this.producto.departamento.id)
           .toPromise()
           .then(
           data => {
              this.prov=data;
              this.empleados=this.prov;
              for (var i = 0; i < this.empleados.length; i++) {
                if(this.empleados[i].rol==0) {
                  this.empleados[i].nombreRol='ADMIN';
                }else if(this.empleados[i].rol==1) {
                  this.empleados[i].nombreRol='SUPERVISOR';
                }else if(this.empleados[i].rol==2) {
                  this.empleados[i].nombreRol='EMPLEADO';
                }
              }
              console.log(this.empleados);
              this.empleado_id=this.producto.usuario.id;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
      //console.log(this.informacion);
    }

    traxas(stock_id,cantidad,d_emisor_id,d_receptor_id,u_emisor_id,u_receptor_id,operacion_id,tipo){
      var sendTraza={
       stock_id:stock_id,
       cantidad:cantidad,
       d_emisor_id:d_emisor_id,
       d_receptor_id:d_receptor_id,
       u_emisor_id:u_emisor_id,
       u_receptor_id:u_receptor_id,
       operacion_id:operacion_id,
       tipo:tipo
      }
      console.log(sendTraza);
      this.http.post(this.ruta.get_ruta()+'trazas',sendTraza)
       .toPromise()
       .then(
       data => {
         console.log(data);
         console.log('éxito al registrar la traza');
        },
       msg => { 
         console.log(msg);
         console.log('Error: '+JSON.stringify(msg));
      });
    }

    pickingEntrega(item,cantidad){
      console.log(item);
      if(item.stock>cantidad && this.cantidadSolicitada>cantidad) {
        alert('entrego completo o parcial');
      }
      if(item.stock<cantidad) {
        alert('entrega parcial y genero una nueva solicitud');
      }
      if(item.stock==cantidad && this.cantidadSolicitada==cantidad) {
        alert('entrega completa');
      }
      this.cantidadSolicitada=this.cantidadSolicitada-cantidad;
    }

    picking(item,tipo){
      console.log(item);
      this.producto.observacion="se entrego completo desde el almacen 1";
      if(tipo==1) {
        item.almacen='principal';
        var send = {
          picking: JSON.stringify(item)
        }
        
        /*this.http.post(this.ruta.get_ruta()+'pedidos/picking',send)
             .toPromise()
             .then(
             data => {
               console.log(data);
               var rec:any;
               rec=data;
               console.log(this.producto);
               console.log(rec);
               console.log(rec.picking);
               for (var i = 0; i < this.informacion.solicitud.length; i++) {
                 if(this.informacion.solicitud[i].id==rec.picking.id) {
                   this.informacion.solicitud[i]=rec.picking;
                 }
               }
               this.producto.stock=this.producto.stock-item.pivot.cantidad;
               this.showP1=false;
               this.showP2=false;
               alert('éxito');
               this.traxas(item.pivot.stock_id,item.pivot.cantidad,100,item.departamento.id,localStorage.getItem('tecprecinc_usuario_id'),this.empleado_id,item.pivot.pedido_id,'Picking');
              },msg => { 
               console.log(console.log(msg.error));
               alert(msg.error);
             });*/
      }else if(tipo==2){
        this.producto.observacion="se entrego completo desde el almacen 2";
        item.almacen='secundario';
        console.log(item);
        var send = {
          picking: JSON.stringify(item)
        }

        /*this.http.post(this.ruta.get_ruta()+'pedidos/picking',send)
             .toPromise()
             .then(
             data => {
               console.log(data);
               var rec:any;
               rec=data;
               console.log(this.producto);
               console.log(rec);
               console.log(rec.picking);
               for (var i = 0; i < this.informacion.solicitud.length; i++) {
                 if(this.informacion.solicitud[i].id==rec.picking.id) {
                   this.informacion.solicitud[i]=rec.picking;
                 }
               }
               this.producto.stock2=this.producto.stock-item.pivot.cantidad;
               this.showP1=false;
               this.showP2=false;
               alert('éxito');
               this.traxas(item.pivot.stock_id,item.pivot.cantidad,101,item.departamento.id,localStorage.getItem('tecprecinc_usuario_id'),this.empleado_id,item.pivot.pedido_id,'Picking');
              },
             msg => { 
               console.log(msg.error);
               alert('Error: '+JSON.stringify(msg));
             });
          */
      }
    }  

    picking2(item,tipo,stock){
      this.producto.observacion="se entrego desde el almacen 1 y se genero una nuevla solicitud ";
      var diferencia= item.pivot.cantidad-stock;
      console.log(diferencia);
      item.pivot.cantidad=stock;
      if(item.observacion=='') {
        item.observacion='Se entregó solo '+ stock +' unidades que habia en stock';
      }
      console.log(item);
      var solicitud = [{
        cantidad:diferencia,
        categoria_id:item.categoria_id,
        centro_costos_id:item.centro_costos.id,
        codigo:item.codigo,
        id:item.id,
        nombre:item.nombre,
        //precio:item.,
        producto_id:item.producto_id,
        rubro_id:item.rubro_id,
        stock:item.stock,
        stock2:item.stock2,
        stock2_min:item.stock2_min,
        stock_min:item.stock_min,
        tipo_id:item.tipo_id
      }];
      var enviar = {
        usuario_id: item.usuario_id,
        solicitud: JSON.stringify(solicitud),
        solicitud2: solicitud,
        centro_costos_id:43,
        contrato_id:1,
        estado: 1,
        aprobar:1,
        observaciones:this.producto.observacion+' de la solicitud Nº'+item.pivot.pedido_id
      }
      console.log(enviar);
      if(tipo==1) {
        item.almacen='principal';
        var send = {
          picking: JSON.stringify(item)
        }
        
        /*this.http.post(this.ruta.get_ruta()+'pedidos/picking',send)
             .toPromise()
             .then(
             data => {
               console.log(data);
               var rec:any;
               rec=data;
               console.log(this.producto);
               console.log(rec);
               console.log(rec.picking);
               for (var i = 0; i < this.informacion.solicitud.length; i++) {
                 if(this.informacion.solicitud[i].id==rec.picking.id) {
                   this.informacion.solicitud[i]=rec.picking;
                 }
               }
               this.producto.stock=this.producto.stock-item.pivot.cantidad;
               this.showP1=false;
               this.showP2=false;
               alert('éxito al realizar el picking');
               this.traxas(item.pivot.stock_id,item.pivot.cantidad,100,item.departamento.id,localStorage.getItem('tecprecinc_usuario_id'),this.empleado_id,item.pivot.pedido_id,'Picking');
             
               //genero un pedido por el faltante
              
                setTimeout(() => {
                  this.http.post(this.ruta.get_ruta()+'pedidos',enviar)
                   .toPromise()
                   .then(
                   data => {
                     console.log(data);
                      
                     alert('Se ha generado una nueva solicitud por la cantidad del producto que no se entregó.');
                    },
                   msg => { 
                     console.log(msg);
                     alert('Error! No se ha generado una nueva solicitud por la cantidad del producto que no se entregó.');
                   });
                }, 1000);
             },msg => { 
               console.log(msg);
               alert('Error: '+JSON.stringify(msg));
             });*/
      }else if(tipo==2){
        this.producto.observacion="se entrego desde el almacen 2 y se genero una nuevla solicitud ";
        item.almacen='secundario';
        console.log(item);
        var send = {
          picking: JSON.stringify(item)
        }

        /*this.http.post(this.ruta.get_ruta()+'pedidos/picking',send)
             .toPromise()
             .then(
             data => {
               console.log(data);
               var rec:any;
               rec=data;
               console.log(this.producto);
               console.log(rec);
               console.log(rec.picking);
               for (var i = 0; i < this.informacion.solicitud.length; i++) {
                 if(this.informacion.solicitud[i].id==rec.picking.id) {
                   this.informacion.solicitud[i]=rec.picking;
                 }
               }
               this.producto.stock2=this.producto.stock-item.pivot.cantidad;
               this.showP1=false;
               this.showP2=false;
               alert('éxito');
               this.traxas(item.pivot.stock_id,item.pivot.cantidad,101,item.departamento.id,localStorage.getItem('tecprecinc_usuario_id'),this.empleado_id,item.pivot.pedido_id,'Picking');
               //genero un pedido por el faltante
              
                setTimeout(() => {
                  this.http.post(this.ruta.get_ruta()+'pedidos',enviar)
                   .toPromise()
                   .then(
                   data => {
                     console.log(data);
                      
                     alert('Se ha generado una nueva solicitud por la cantidad del producto que no se entregó.');
                    },
                   msg => { 
                     console.log(msg);
                     alert('Error! No se ha generado una nueva solicitud por la cantidad del producto que no se entregó.');
                   });
                }, 1000);

              },
             msg => { 
               console.log(msg);
               alert('Error: '+JSON.stringify(msg));
             });*/
      }
    } 

    picking3(item,tipo){
      this.producto.observacion="se entrego parcialmente";
      console.log(item);
      if(tipo==1) {
        item.almacen='principal';
        var send = {
          picking: JSON.stringify(item)
        }
        
        /*this.http.post(this.ruta.get_ruta()+'pedidos/picking',send)
             .toPromise()
             .then(
             data => {
               console.log(data);
               var rec:any;
               rec=data;
               console.log(this.producto);
               console.log(rec);
               console.log(rec.picking);
               for (var i = 0; i < this.informacion.solicitud.length; i++) {
                 if(this.informacion.solicitud[i].id==rec.picking.id) {
                   this.informacion.solicitud[i]=rec.picking;
                 }
               }
               this.producto.stock=this.producto.stock-item.pivot.cantidad;
               this.showP1=false;
               this.showP2=false;
               alert('éxito');
               this.traxas(item.pivot.stock_id,item.pivot.cantidad,100,item.departamento.id,localStorage.getItem('tecprecinc_usuario_id'),this.empleado_id,item.pivot.pedido_id,'Picking');
              },msg => { 
               console.log(console.log(msg.error));
               alert(msg.error);
             });*/
      }else if(tipo==2){
        item.almacen='secundario';
        console.log(item);
        var send = {
          picking: JSON.stringify(item)
        }

        /*this.http.post(this.ruta.get_ruta()+'pedidos/picking',send)
             .toPromise()
             .then(
             data => {
               console.log(data);
               var rec:any;
               rec=data;
               console.log(this.producto);
               console.log(rec);
               console.log(rec.picking);
               for (var i = 0; i < this.informacion.solicitud.length; i++) {
                 if(this.informacion.solicitud[i].id==rec.picking.id) {
                   this.informacion.solicitud[i]=rec.picking;
                 }
               }
               this.producto.stock2=this.producto.stock-item.pivot.cantidad;
               this.showP1=false;
               this.showP2=false;
               alert('éxito');
               this.traxas(item.pivot.stock_id,item.pivot.cantidad,101,item.departamento.id,localStorage.getItem('tecprecinc_usuario_id'),this.empleado_id,item.pivot.pedido_id,'Picking');
              },
             msg => { 
               console.log(msg.error);
               alert('Error: '+JSON.stringify(msg));
             });
          */
      }
    }   
}
