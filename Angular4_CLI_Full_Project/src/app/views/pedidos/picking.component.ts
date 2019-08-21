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
  public cantidadSolicitada:any=0;
  public cantidadSolicitada2:any=0;
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
  public obs='';

  @Input() producto:any;
  @Input() informacion:any;

  constructor(private http: HttpClient, private ruta: RutaService,private sharedService: SharedService) {
    this.sharedService.cartData.subscribe(
          (data: any) => {
            console.log(data);
            this.showP1=true;
            this.showP2=true;
            this.checkCant=true;
            this.numEntregar=0;
            setTimeout(()=>{
              this.cantidadSolicitada=parseInt(this.producto.pivot.cantidad);
              this.cantidadSolicitada2=parseInt(this.producto.pivot.cantidad);
              this.cantEntregar1=parseInt(this.producto.pivot.cantidad);
              this.itemEntregar=[];
              this.numEntregar=0;
              this.obs='';
              this.producto.observacion=this.obs;
              this.entregado=true;
              console.log(data);
            },1000);
            
            //alert('chato');
          });
  }


   public infoUsuario:any;
   public infoDepartamento:any;
   ngOnInit(): void {
       this.loading=false;
      console.log(this.producto);
      this.cantidadSolicitada=parseInt(this.producto.pivot.cantidad);
      this.infoUsuario=this.informacion.usuario;
      this.infoDepartamento=this.informacion.departamento;
      this.cantidadSolicitada2=parseInt(this.producto.pivot.cantidad);
      this.cantEntregar1=parseInt(this.producto.pivot.cantidad);
      this.producto.bstock=true;
      this.producto.bstock2=true;
      this.producto.observacion=this.obs;
      
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


    public itemEntregar:any=[];
    public numEntregar=0;

    //if stock!=0
     // if cantidad!=0
      
       // 1 caso: Stock>cantidad normal
         // pero: modifico cantidad original pasa a ser parcial
       // 2 caso: Stock<cantidad genero una nueva
         // pero: modifico cantidad original pasa a ser parcial
    checkCantidadPicking1(item,cantidad){
      console.log(item.stock+' '+this.cantEntregar1);

      if(item.stock<this.cantEntregar1 && this.cantEntregar1!=this.cantidadSolicitada2) {
        this.cantEntregar1=0;
      }
    }
    checkCantidadPicking2(item,cantidad){
      console.log(item.stock+' '+this.cantEntregar2);
      if(item.stock2<this.cantEntregar2  && this.cantEntregar2!=this.cantidadSolicitada2) {
        this.cantEntregar2=0;
      }
    }
    pickingEntrega(item,cantidad,almacen){
      if(almacen==1) {
        item.almacen='principal';
        if(item.stock!=0) {
          if(cantidad!=0) {
            if(item.stock>=cantidad){
              if(this.cantidadSolicitada-cantidad>=0) {
                this.producto.observacion+= ' Entrega '+cantidad+' desde almacen 1.';
                this.cantidadSolicitada=this.cantidadSolicitada-cantidad;
                this.numEntregar+=parseInt(cantidad);
                let estoyEntregando={
                  cantidad:cantidad,
                  item:item,
                  almacen:almacen
                }
                this.itemEntregar.push(estoyEntregando);
                this.showP1=false;
              }     
              
            }
            else if(item.stock<cantidad) {
              if(this.cantidadSolicitada-cantidad>=0) {
                this.producto.observacion+= ' Entrego '+item.stock+' desde almacen 1 y genero una nueva solicitud a '+item.pivot.pedido_id+'.';
                this.cantidadSolicitada=this.cantidadSolicitada-item.stock;
                this.numEntregar+=parseInt(item.stock);
                let estoyEntregando={
                  cantidad:item.stock,
                  item:item,
                  almacen:almacen
                }
                this.itemEntregar.push(estoyEntregando);
                this.showP1=false;
              }
              
            }
          }
        }
      }else if(almacen==2) {
        item.almacen='secundario';
        if(item.stock2!=0) {
          if(cantidad!=0) {
            if(item.stock2>=cantidad){
              if(this.cantidadSolicitada-cantidad>=0) {
                this.producto.observacion+= ' Entrega '+cantidad+' desde almacen 2.';
                this.cantidadSolicitada=this.cantidadSolicitada-cantidad;
                this.numEntregar+=parseInt(cantidad);
                let estoyEntregando={
                  cantidad:cantidad,
                  item:item,
                  almacen:almacen
                }
                this.itemEntregar.push(estoyEntregando);
                this.showP2=false;
              }
              
            }else if(item.stock2<cantidad) {
              if(this.cantidadSolicitada-cantidad>=0) {
                this.producto.observacion+= ' Entrego '+item.stock2+' desde almacen 2 y genero una nueva solicitud a '+item.pivot.pedido_id+'.';
                this.cantidadSolicitada=this.cantidadSolicitada-item.stock2;
                this.numEntregar+=parseInt(item.stock2);
                let estoyEntregando={
                  cantidad:item.stock2,
                  item:item,
                  almacen:almacen
                }
                this.itemEntregar.push(estoyEntregando);
                this.showP2=false;
              }
              
            }
          }
        }
      }
      this.checkCantidad();
    }
    public checkCant=true;
    checkCantidad(){
      if(this.numEntregar>=this.cantidadSolicitada2){
        //alert('ya');
        this.checkCant=false;
      }
    }
    public almacenE:any=0;
    public entregado=true;
    entrega(){
      for (var i = 0; i < this.itemEntregar.length; i++) {
        console.log(this.itemEntregar[i]);
        this.almacenE=this.itemEntregar[i].almacen;
        this.itemEntregar[i].item.pivot.cantidad=this.itemEntregar[i].cantidad;
        this.itemEntregar[i].item.observacion=this.producto.observacion;
        //this.picking(this.itemEntregar[i].item,this.itemEntregar[i].almacen);
        var item:any = this.itemEntregar[i].item;
        var send = {
            picking: JSON.stringify(item)
          }

        this.http.post(this.ruta.get_ruta()+'pedidos/picking',send)
             .toPromise()
             .then(
             data => {
               console.log(data);
               var rec:any;
               rec=data;
               console.log(this.producto);
               console.log(rec);
               console.log(rec.picking);
               for (var j = 0; j < this.informacion.solicitud.length; j++) {
                 if(this.informacion.solicitud[j].id==rec.picking.id) {
                   this.informacion.solicitud[j]=rec.picking;
                 }
               }

               alert('éxito');
               console.log(this.almacenE);
               if(this.almacenE==1) {
                 this.traxas(item.pivot.stock_id,item.pivot.cantidad,100,item.departamento.id,localStorage.getItem('tecprecinc_usuario_id'),this.empleado_id,item.pivot.pedido_id,'Picking');
               }
               if(this.almacenE==2) {
                 this.traxas(item.pivot.stock_id,item.pivot.cantidad,101,item.departamento.id,localStorage.getItem('tecprecinc_usuario_id'),this.empleado_id,item.pivot.pedido_id,'Picking');
               }
               this.entregado=false;
              },msg => { 
               console.log(console.log(msg.error));
               alert(msg.error);
              });
      }
    }

    public diferencia=0;
    entregaygenera(){
      this.diferencia=this.cantidadSolicitada2-this.numEntregar;
      if(this.itemEntregar.length==1) {
        this.almacenE=this.itemEntregar[0].almacen;
        this.itemEntregar[0].item.pivot.cantidad=this.itemEntregar[0].cantidad;
        this.itemEntregar[0].item.observacion=this.producto.observacion;
        //this.picking2(this.itemEntregar[0].item,this.itemEntregar[0].almacen,this.itemEntregar[0].stock);
        var item:any = this.itemEntregar[0].item;
        console.log(item);
        var solicitud = [{
        cantidad:this.diferencia,
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
        tipo_id:item.tipo_id,
        usuario:this.infoUsuario,
        departamento:this.infoDepartamento,
        observaciones:this.producto.observacion
      }];
      var enviar = {
        usuario_id: item.usuario_id,
        departamento_id:this.infoDepartamento.id,
        solicitud: JSON.stringify(solicitud),
        solicitud2: solicitud,
        centro_costos_id:43,
        contrato_id:1,
        estado: 1,
        aprobar:1,
        usuario:this.infoUsuario,
        departamento:this.infoDepartamento,
        observaciones:this.producto.observacion
      }
      item.usuario=this.infoUsuario;
      item.departamento=this.infoDepartamento;
      console.log(enviar);
      console.log(item);
      var send = {
          picking: JSON.stringify(item)
        }
        
      this.http.post(this.ruta.get_ruta()+'pedidos/picking',send)
             .toPromise()
             .then(
             data => {
               console.log(data);
               var rec:any;
               rec=data;
               console.log(this.producto);
               console.log(rec);
               console.log(rec.picking);
               for (var j = 0; j < this.informacion.solicitud.length; j++) {
                 if(this.informacion.solicitud[j].id==rec.picking.id) {
                   this.informacion.solicitud[j]=rec.picking;
                 }
               }
              
               alert('éxito');
               if(this.almacenE==1) {
                 this.traxas(item.pivot.stock_id,item.pivot.cantidad,100,item.departamento.id,localStorage.getItem('tecprecinc_usuario_id'),this.empleado_id,item.pivot.pedido_id,'Picking');
               }
               if(this.almacenE==2) {
                 this.traxas(item.pivot.stock_id,item.pivot.cantidad,101,item.departamento.id,localStorage.getItem('tecprecinc_usuario_id'),this.empleado_id,item.pivot.pedido_id,'Picking');
               }
               this.entregado=false;
               
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
             });
      }else{
        alert('Tiene que seleccionar desde un solo almacen.');
      }
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