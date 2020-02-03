# https://github.com/ddanielsouza/consulta-pessoa-fisica-utils #

Projeto de código utilitários usado nos micro-serviços "Consulta pessoa física"

### Pré-Requisitos: ##
Esse ultilitario foi codificado para o Lumen PHP Framework, rodado e testado sobre a versão 6.0 do Lumen e PHP 7.2,
e pode ser usado com o GIT ou inserido manualmente no projeto.

### Para começar ###

<h4> Git </h4>:

- `git submodule add https://github.com/ddanielsouza/consulta-pessoa-fisica-utils.git app/Utils`
  

- `git submodule init `


- `git submodule update `

### Para buscar novas atualizações

- `cd app/Utils`

- `git pull`

<p>MANUAL:</p>
Baixe o projeto e o adicione no diretório **app/Utils**

<p>Estrutura</p>

<ul>
  <li>
    app
    <ul>
      <li>
        Utils
        <ul>
          <li>Controllers</li>
          <li>Facades</li>
          <li>Helpers</li>
          <li>Middleware</li>
          <li>Providers</li>
        </ul
      </li>
    </ul>
  </li>
</ul>

### Utilização do Projeto ###

#### ControllerModel ####

ControllerModel fornece 6 functions basicos para operações de API REST
<ul>
  <li>
    <p>getById</p>
    passando o parametro "relations" busca os relacionamentos da model
    <br>
    Ex:
    <blockquote>
    [GET] http://domain/api/financial-movement?relations=["clients"]
   </blockquote>
  </li>
  <li>
    <p>save</p>
    "save" usa como validação o <a href="https://laravel.com/docs/5.8/validation">"Validator"</a> e deve se especificado no atributo "$basicValidate"
  </li>
  <li>
    <p>update</p>
    deve passar o id do registro via "pretty" url 
    <blockquote>
    [POST] http://domain/api/financial-movement/1]
    </blockquote>
    Validação idem a function save
  </li>
  <li>
    <p>patch</p>
    Atualizar e valida só os campos enviado via request
  </li>
  <li>
    <p>delete</p>
  </li>
  <li>
    <p> get </p>
    Buscar e filtrar os registros da model. Campos de hash md5 deve ser especificado em  $columnsEncrypted para usar na filtragem
  </li>
</ul>

```php
<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Utils\Controllers\ControllerModel;


class AddressController extends ControllerModel
{
    protected $modelName = Address::class;
    protected $basicValidate = [
        'material_asset_id'=>'required|numeric',
        'cod_ibge' => 'required|numeric',
        'dctZipCode' => 'required|regex:/\\d{8}/im',
        'dctStreetAddress' => 'required|string',
        'dctComplement' => 'required|string',
        'dctNeighborhood' => 'required|string',
    ];
    protected $columnsEncrypted = ['dctZipCode' => 'hash_zip_code'];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => []]);
    }
}

``` 
<br>

```PHP
    $router->group(['prefix'=>'address'], function() use ($router){
        $router->post('/', 'AddressController@save');
        $router->get('/', 'AddressController@get');
        $router->get('/{id}', 'AddressController@getById');
        $router->put('/{id}', 'AddressController@update');
        $router->patch('/{id}', 'AddressController@patch');
        $router->delete('/{id}', 'AddressController@delete');
    });
 ``` 

Outras classes auxiliares 
<ul>
  <li> App\Utils\Middleware\CorsMiddleware::class : habilita o "cors" (Está configurado para "localhost:8080") </li>
  <li>App\Utils\Facades\APIAuth : "Fachada" para classe de comunicação com a  <a href="https://github.com/ddanielsouza/consulta-pessoa-fisica-auth">API de autenticação</a></li>
  <li> App\Utils\Helpers\ISOSerialization : Serializa as datas para ISO ao passar para JSON </li>
</ul>
