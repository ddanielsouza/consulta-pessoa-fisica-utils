# https://github.com/ddanielsouza/consulta-pessoa-fisica-utils #

Projeto de código utilitários usado nos micro-serviços "Consulta pessoa física"

### PRÉ-REQUISITOS: ##
Esse ultilitario foi codificado para o Lumen PHP Framework, rodado e testado sobre a versão 6.0 do Lumen e PHP 7.2,
e pode ser usado com o GIT ou inserido manualmente no projeto.

### COMEÇAR ###
GIT:
```
  git submodule add https://github.com/ddanielsouza/consulta-pessoa-fisica-utils.git app/Utils
```

```
  git submodule init
```

```
  git submodule update
```

PARA BUCAR NOVAS ATUALIZAÇÕES

```
  cd app/Utils
```

```
  git pull
```
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

### USANDO ###

#### ControllerModel ####

ControllerModel fornece 6 methodos basicos para operações de API REST
<ul>
  <li>
    <p>getById</p>
    passando o parametro "relations" busca os relacionamentos da model
    <br>
    Ex:
    <blockquote>
    CURL http://domain/api/financial-movement?relations=["clients"]
   </blockquote>
  </li>
  <li>
    <p>save</p>
    "save" usa como validação o <a href="https://laravel.com/docs/5.8/validation">"Validator"</a> e deve se especificado no atributo "$basicValidate"
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
