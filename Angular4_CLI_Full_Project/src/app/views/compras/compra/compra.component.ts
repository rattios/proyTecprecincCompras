import { Component } from '@angular/core';
import {CommonModule} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { saveAs } from 'file-saver/FileSaver';
import { RutaService } from '../../../services/ruta.service';

@Component({
  templateUrl: 'compra.component.html'
})
export class compraComponent {
  public prov: any;
  public stock: any;
  public proveedores: any;
  public proveedores2: any;
  public productos: any;
  public loading=true;
  public loading2=true;
  public nota:any='';
  public proveedor: any={
          calificacion:null,
          cuit:"",
          email:"",
          estado:"",
          fax:"",
          habilitado:"NO",
          habilitado2: false,
          motivo:"",
          id:0,
          nombre_fantacia:"",
          productos:[],
          razon_social:"",
          telefono:"",
          direccion: "",
          formaPago: "",
          telefonos: []
        };
        
  constructor(private http: HttpClient, private ruta: RutaService) {

  }

  ngOnInit(): void {
    this.loading=true;
      this.http.get(this.ruta.get_ruta()+'proveedores')
           .toPromise()
           .then(
           data => {
             this.prov=data;
              this.proveedores=this.prov.proveedores;
              this.proveedores2=this.prov.proveedores;
              console.log(this.proveedores);
              this.productList = this.proveedores;
              this.filteredItems = this.productList;
              this.init();
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });

      this.http.get(this.ruta.get_ruta()+'stock')
           .toPromise()
           .then(
           data => {
             this.prov=data;
               this.stock=this.prov.productos;
              console.log(this.stock);
              for (var i = 0; i < this.stock.length; i++) {
                if(this.stock.habilitado=="SI"){
                  this.stock.habilitado=true;
                }
              }
              this.productList2 = this.stock;
              this.filteredItems2 = this.productList2;
              this.init2();
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
  }
  seleccionar(item){
    this.proveedor=item;
    
  }
  del(){
    this.proveedor={
          calificacion:null,
          cuit:"",
          email:"",
          estado:"",
          fax:"",
          habilitado:"NO",
          habilitado2: false,
          motivo:"",
          id:0,
          nombre_fantacia:"",
          productos:[],
          razon_social:"",
          telefono:"",
          direccion: "",
          formaPago: "",
          telefonos: []
        };
    this.itemsPresupuesto=[];
  }
  setProveedores(){
    console.log(this.proveedores2);
    this.proveedores=this.proveedores2;
    this.productList = this.proveedores;
    this.filteredItems = this.productList;
    this.init();
  }
  public itemsPresupuesto:any=[];
  add(item){
    if(!this.checkProductos(item)) {
     item.cantidad=1;
     item.factura='';
     item.entregado=0;
     this.itemsPresupuesto.push(item);
    }
  }
  set(item){

    this.http.get(this.ruta.get_ruta()+'productos/'+item.id+'/proveedores')
           .toPromise()
           .then(
           data => {
              console.log(data);
              this.prov=data;
              this.proveedores=this.prov.producto.proveedores;
              for (var i = 0; i < this.proveedores.length; i++) {
                for (var j = 0; j < this.proveedores2.length; j++) {
                  if(this.proveedores[i].razon_social==this.proveedores2[j].razon_social) {
                    this.proveedores[i]=this.proveedores2[j];
                  }
                }
              }
              console.log(this.proveedores);
              this.productList = this.proveedores;
              this.filteredItems = this.productList;
              this.init();
              this.loading=false;
              if(!this.checkProductos(item)) {
               item.cantidad=1;
               item.factura='';
               item.entregado=0;
               this.itemsPresupuesto.push(item);
              }
              if(this.proveedores.length==0) {
                this.del();
              }
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
  }
  checkProductos(item){
      var band=false;
      for (var i = 0; i < this.itemsPresupuesto.length; i++) {
        if(this.itemsPresupuesto[i].id==item.id) {
          band=true;
        }
      }
      return band;
    }
    eliminarProducto(it){
      for (var i = 0; i < this.itemsPresupuesto.length; i++) {
        if(this.itemsPresupuesto[i].id==it.id) {
          this.itemsPresupuesto.splice(i, 1);
        }
      }
    }
    public observaciones:any;
    public enviado:any={
      razon_social:'',
      nombre_fantasia:'',
      cuit:'',
      telefono:'',
      email:'',
      pedido_id:0,
      estado:0,
      proveedor_id:0,
      productos:[],
      observaciones:''
    };
    public aEnviar:any={
      razon_social:'',
      nombre_fantasia:'',
      cuit:'',
      telefono:'',
      email:'',
      pedido_id:21,
      estado:0,
      proveedor_id:0,
      productos:[],
      observaciones:'',
      documento:''
    };
    enviarPresupuesto(){
      this.loading=true;
      if(this.itemsPresupuesto.length>0) {
        this.aEnviar.razon_social=this.proveedor.razon_social,
        this.aEnviar.nombre_fantasia=this.proveedor.nombre_fantasia,
        this.aEnviar.cuit=this.proveedor.cuit,
        this.aEnviar.telefono=this.proveedor.telefono,
        this.aEnviar.email=this.proveedor.email,
        this.aEnviar.proveedor_id=this.proveedor.id;
        this.aEnviar.nota=this.nota;
        this.aEnviar.productos=JSON.stringify(this.itemsPresupuesto);
        this.aEnviar.observaciones=this.observaciones;
        console.log(this.aEnviar);
        this.http.post(this.ruta.get_ruta()+'compra',this.aEnviar)
           .toPromise()
           .then(
           data => {
             console.log(data);
             this.enviado=data;
             this.enviado=this.enviado.compra;
             this.enviado.productos=JSON.parse(this.enviado.productos);
             this.itemsPresupuesto=[];
             this.observaciones='';
             this.nota='';
             this.loading=false;
             this.saveFile();

            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
      }
      console.log(this.aEnviar);
    }

  public saveFile(){
    setTimeout(() => {
      console.log('exportar');
      let printContents, popupWin;
      printContents = document.getElementById('exportable').innerHTML;
      popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
      popupWin.document.open();
      popupWin.document.write(`
      <html>
        <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
          
          <style>
            @media print {
              @page { margin: 0; }
              body { margin: 1.6cm; }
              .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
                    float: left;
               }
               .col-sm-12 {
                    width: 100%;
               }
               .col-sm-11 {
                    width: 91.66666667%;
               }
               .col-sm-10 {
                    width: 83.33333333%;
               }
               .col-sm-9 {
                    width: 75%;
               }
               .col-sm-8 {
                    width: 66.66666667%;
               }
               .col-sm-7 {
                    width: 58.33333333%;
               }
               .col-sm-6 {
                    width: 50%;
               }
               .col-sm-5 {
                    width: 41.66666667%;
               }
               .col-sm-4 {
                    width: 33.33333333%;
               }
               .col-sm-3 {
                    width: 25%;
               }
               .col-sm-2 {
                    width: 16.66666667%;
               }
               .col-sm-1 {
                    width: 8.33333333%;
               }
            }
          </style>
        </head>
      <body onload="window.print();window.close()"> ${printContents} </body>
      </html>`
      );
      popupWin.document.close();
    }, 2000)
      
  }




     //-------------------------------------------------------------------------------------------------------------------------
   
   filteredItems : any;
   productList : any;
   pages : number = 4;
   pageSize : number = 5;
   pageNumber : number = 6;
   currentIndex : number = 1;
   items: any;
   pagesIndex : Array<number>;
   pageStart : number = 1;
   inputName : string = '';

   init(){
         this.currentIndex = 1;
         this.pageStart = 1;
         this.pages = 4;

         this.pageNumber = parseInt(""+ (this.filteredItems.length / this.pageSize));
         if(this.filteredItems.length % this.pageSize != 0){
            this.pageNumber ++;
         }
    
         if(this.pageNumber  < this.pages){
               this.pages =  this.pageNumber;
         }
       
         this.refreshItems();
         console.log("this.pageNumber :  "+this.pageNumber);
   }
   FilterByName(){
      this.filteredItems = [];
      if(this.inputName != ""){
            for (var i = 0; i < this.productList.length; ++i) {

              if (this.productList[i].razon_social.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].cuit.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].telefono.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].email.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }
            }

            // this.productList.forEach(element => {
            //     if(element.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0){
            //       this.filteredItems.push(element);
            //    }
            // });
      }else{
         this.filteredItems = this.productList;
      }
      console.log(this.filteredItems);
      this.init();
   }
   fillArray(): any{
      var obj = new Array();
      for(var index = this.pageStart; index< this.pageStart + this.pages; index ++) {
                  obj.push(index);
      }
      return obj;
   }
 refreshItems(){
               this.items = this.filteredItems.slice((this.currentIndex - 1)*this.pageSize, (this.currentIndex) * this.pageSize);
               this.pagesIndex =  this.fillArray();
   }
   prevPage(){
      if(this.currentIndex>1){
         this.currentIndex --;
      } 
      if(this.currentIndex < this.pageStart){
         this.pageStart = this.currentIndex;
      }
      this.refreshItems();
   }
   nextPage(){
      if(this.currentIndex < this.pageNumber){
            this.currentIndex ++;
      }
      if(this.currentIndex >= (this.pageStart + this.pages)){
         this.pageStart = this.currentIndex - this.pages + 1;
      }
 
      this.refreshItems();
   }
    setPage(index : number){
         this.currentIndex = index;
         this.refreshItems();
    }

    //-------------------------------------------------------------------------------------------------------------------------
   
   filteredItems2 : any;
   productList2 : any;
   pages2 : number = 4;
   pageSize2 : number = 5;
   pageNumber2 : number = 0;
   currentIndex2 : number = 1;
   items2: any;
   pagesIndex2 : Array<number>;
   pageStart2 : number = 1;
   inputName2 : string = '';

   init2(){
         this.currentIndex2 = 1;
         this.pageStart2 = 1;
         this.pages2 = 4;

         this.pageNumber2 = parseInt(""+ (this.filteredItems2.length / this.pageSize2));
         if(this.filteredItems2.length % this.pageSize2 != 0){
            this.pageNumber2 ++;
         }
    
         if(this.pageNumber2  < this.pages2){
               this.pages2 =  this.pageNumber2;
         }
       
         this.refreshItems2();
         console.log("this.pageNumber :  "+this.pageNumber2);
   }
   FilterByName2(){
      this.filteredItems2 = [];
      if(this.inputName2 != ""){
            for (var i = 0; i < this.productList2.length; ++i) {

              if (this.productList2[i].stock==this.inputName2) {
                 this.filteredItems2.push(this.productList2[i]);
              }else if (this.productList2[i].nombre.toUpperCase().indexOf(this.inputName2.toUpperCase())>=0) {
                 this.filteredItems2.push(this.productList2[i]);
              }else if (this.productList2[i].precio==this.inputName2) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList2[i].codigo==this.inputName2) {
                 this.filteredItems2.push(this.productList2[i]);
              }
              
            }

            // this.productList.forEach(element => {
            //     if(element.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0){
            //       this.filteredItems.push(element);
            //    }
            // });
      }else{
         this.filteredItems2 = this.productList2;
      }
      //console.log(this.filteredItems2);
      this.init2();
   }
   fillArray2(): any{
      var obj2 = new Array();
      for(var index2 = this.pageStart2; index2< this.pageStart2 + this.pages2; index2 ++) {
                  obj2.push(index2);
      }
      return obj2;
   }
 refreshItems2(){
               this.items2 = this.filteredItems2.slice((this.currentIndex2 - 1)*this.pageSize2, (this.currentIndex2) * this.pageSize2);
               this.pagesIndex2 =  this.fillArray();
               console.log(this.items2);
   }
   prevPage2(){
      if(this.currentIndex2>1){
         this.currentIndex2 --;
      } 
      if(this.currentIndex2 < this.pageStart2){
         this.pageStart2 = this.currentIndex2;
      }
      this.refreshItems2();
   }
   nextPage2(){
      if(this.currentIndex2 < this.pageNumber2){
            this.currentIndex2 ++;
      }
      if(this.currentIndex2 >= (this.pageStart2 + this.pages2)){
         this.pageStart2 = this.currentIndex2 - this.pages2 + 1;
      }
 
      this.refreshItems2();
   }
    setPage2(index : number){
         this.currentIndex2 = index;
         this.refreshItems2();
    }
    
}
