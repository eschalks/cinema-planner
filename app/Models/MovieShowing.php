<?php


namespace App\Models;


/**
 * App\Models\MovieShowing
 *
 * @property int $id
 * @property int $movie_id
 * @property string $source
 * @property string $starts_at
 * @property string $ends_at
 * @property int $3d
 * @property int $quality
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MovieShowing where3d($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MovieShowing whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MovieShowing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MovieShowing whereMovieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MovieShowing whereQuality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MovieShowing whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MovieShowing whereStartsAt($value)
 * @mixin \Eloquent
 */
class MovieShowing extends BaseModel
{

}
