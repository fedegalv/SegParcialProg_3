<?php
//CREAR NAMESPACE
namespace App\Models;
// Y AGREGAR EN COMPOSER !!

//EXTENDER Usuario PARA QUE UTILICE ELOQUENT
//ESTA CLASE MANEJA LA TABLA alumnos DE LA DB
use Illuminate\Database\Eloquent\Model;

//ELOQUENT AUTOMATICANTE CONECTA Y ASUME EL NOMBRE DE LA BD A TRAVES DEL
//NOMBRE DE LA CLASE, POR EJ Alumno  BUSCA EN LA BD TABLA alumnos
class Alumno extends Model{
    ///EN CASO DE QUE SE QUIERA ESPECIFICAR EL NOMBRE DE LA TABLA A REFERENCIAR
    //USAR LO SIG
    // protected $table = 'alumnos'

    ///POR DEFECTO BUSCA EL CAMPO id EN LA TABLA; SI SE QUIERE CAMBIAR O 
    //ESPECIFICAR SE HACE CON LO SIG
    //protected $primaryKey = 'alumno_id';

    /// POR DEFECTO BUSCA LOS CAMPOS creation_date y last_update DE TIPO TIMESTAMP
    // SI SE QUIERE EVITAR ESTO USAR LO SIG
    public $timestamp = false;

    ///SI SE QUE ESPECIFICAR ESTAS TABLAS HACER LO SIG
    //const CREATED_At = 'creation_date';
    //const UPDATED_AT = 'last_update';
}