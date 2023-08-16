<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Escola
 *
 * @property int $id
 * @property string $nome
 * @property string $cnpj
 * @property string $uf
 * @property string $cidade
 * @property bool $endereco_em_breve
 * @property string|null $cep
 * @property string|null $endereco
 * @property string|null $numero_endereco
 * @property string|null $complemento
 * @property string|null $instagram
 * @property string $celular
 * @property string $logo_path
 * @property int $user_id
 *
 * @property User $user
 *
 * @package App\Models
 */
class Escola extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $connection = 'mysql';
    protected $table      = 'escolas';
    public $timestamps    = false;

    protected $casts = [
        'endereco_em_breve' => 'bool',
        'user_id'           => 'int'
    ];

    protected $fillable = [
        'nome',
        'cnpj',
        'uf',
        'cidade',
        'endereco_em_breve',
        'cep',
        'endereco',
        'numero_endereco',
        'complemento',
        'instagram',
        'celular',
        'logo_path',
        'user_id'
    ];

    /**
     * Monta o endereço completo da escola, caso não tenha informa endereço em breve
     * @author Magalhaes, Felipe <magalhaesbg@gmail.com>
     * @return string
     */
    public function enderecoCompleto(): string
    {
        $endereco = '';
        if (!empty($this->endereco) && !empty($this->numero_endereco)) {
            $endereco .= $this->endereco . ', ' . $this->numero_endereco;

            if (!empty($this->complemento)) {
                $endereco .= ' - ' . $this->complemento;
            }
        } else {
            $endereco = 'Em breve, novo endereço.';
        }

        return $endereco;
    }

    /**
     * Remove a mascara do telefone e adiciona o 55 do brasil ao telefone para whats
     * @author Magalhaes, Felipe <magalhaesbg@gmail.com>
     * @return string
     */
    public function numeroTelefoneSemMascara(): string
    {
        $numeroTelefone = $this->celular;
        $numeroTelefone = preg_replace('/[^0-9]/', '', $numeroTelefone);
        if (substr($numeroTelefone, 0, 2) !== '55') {
            $numeroTelefone = '55' . $numeroTelefone;
        }

        return $numeroTelefone;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
