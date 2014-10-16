<?php
namespace Library;

/**
 * バリデートクラス
 */
class Validate
{
    /**
     * 複数バリデータ
     * @param $param Array( 'キー名' => 'req|num･･･', 'チェックする値' )
     * @return Array エラー文字配列( キーは引数に合わせる )
     * req : 必須
     * num : 整数
     * alph : アルファベット小文字
     * ALPH : アルファベット大文字
     * Alph : アルファベット全て
     * num_Alph : 整数 + アルファベット全て
     * MAX[0-9]+ : 最大文字数
     * MIN[0-9]+ : 最小文字数
     * FIX[0-9]+ : ぴったり文字数
     */
    public function validReq( $param = array() )
    {
        if( $param ) {
            foreach( $param as $key => $val ) {
                $req    = preg_match( '/req/', $val ) ? true : false;;
                $option = explode( '|', $val );
                foreach( $option as $k => $v ) {
                    switch( true ) {
                        case ( 'req' == $v ):
                            if( !$this->isInput( $val ) ) {
                                $this->error[ $key ] = true;
                            }
                        break;
                        case ( 'num' == $v ):
                            if( !$this->isNumeric( $val, $req ) ) {
                                $this->error[ $key ] = true;
                            }
                        break;
                        case ( 'alph' == $v ):
                            if( !$this->isLowerAlphabet( $val, $req ) ) {
                                $this->error[ $key ] = true;
                            }
                        break;
                        case ( 'ALPH' == $v ):
                            if( !$this->isUpperAlphabet( $val, $req ) ) {
                                $this->error[ $key ] = true;
                            }
                        break;
                        case ( 'Alph' == $v ):
                            if( !$this->isAlphabet( $val, $req ) ) {
                                $this->error[ $key ] = true;
                            }
                        break;
                        case ( 'num_Alph' == $v ):
                            if( !$this->isNumericAlphabet( $val, $req ) ) {
                                $this->error[ $key ] = true;
                            }
                        break;
                        case ( preg_match( '/^MAX([0-9]+)$/', $v, $match ) ):
                            if( !$this->isOverNumber( $val, $match[1], $req ) ) {
                                $this->error[ $key ] = true;
                            }
                        break;
                        case ( preg_match( '/^MIN([0-9]+)$/', $v, $match ) ):
                            if( !$this->isUnderNumber( $val, $match[1], $req ) ) {
                                $this->error[ $key ] = true;
                            }
                        break;
                        case ( preg_match( '/^FIX([0-9]+)$/', $v, $match ) ):
                            if( !$this->isFixNumber( $val, $match[1], $req ) ) {
                                $this->error[ $key ] = true;
                            }
                        break;
                        case ( 'date' == $v ):
                            $date = getdate( strtotime( $val ) );
                            if( !$this->isDateTime( $date[ 'year' ], $date[ 'mon' ], $date[ 'mday' ], $date[ 'hours' ], $date[ 'minutes' ], $date[ 'seconds' ] ) ) {
                                $this->error[ $key ] = true;
                            }
                        break;
                        case ( 'email' == $v ):
                            if( !$this->isEmail( $val ) ) {
                                $this->error[ $key ] = true;
                            }
                        break;
                        case ( 'kana' == $v ):
                            if( !$this->isKana( $val ) ) {
                                $this->error[ $key ] = true;
                            }
                        break;
                    }
                }
            }
        }

        return $this->error;
    }

    /**
     * 必須バリデータ
     * @param $value 検査文字列
     * @return Boolean 正否
     */
    public function isInput( $value )
    {
        // 正否判定
        $return = true;

        // 改行は文字カウントしない
        $value = preg_replace( '/\r|\n/', '', $value );
        // スペースのみは文字カウントしない
        $value = preg_replace( '/ |　/', '', $value );

        // チェック
        if( $value == '' ) {
            $return = false;
        }

        return $return;
    }

    /**
     * 整数バリデータ
     * @param $value 検査文字列
     * @param $req 必須フラグ
     * @return Boolean 正否
     */
    public function isNumeric( $value, $req=false )
    {
        // 正否判定
        $return = true;

        if( !is_numeric( $value ) ) {
            if( !( ( $req == false ) && ( $value == '' ) ) ) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * アルファベット小文字バリデータ
     * @param $value 検査文字列
     * @param $req 必須フラグ
     * @return Boolean 正否
     */
    public function isLowerAlphabet( $value, $req=false )
    {
        // 正否判定
        $return = true;

        if( !preg_match( '/^[a-z]+$/', $value ) ) {
            if( !( ( $req == false ) && ( $value == '' ) ) ) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * アルファベット大文字バリデータ
     * @param $value 検査文字列
     * @param $req 必須フラグ
     * @return Boolean 正否
     */
    public function isUpperAlphabet( $value, $req=false )
    {
        // 正否判定
        $return = true;

        if( !preg_match( '/^[A-Z]+$/', $value ) ) {
            if( !( ( $req == false ) && ( $value == '' ) ) ) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * アルファベットバリデータ
     * @param $value 検査文字列
     * @param $req 必須フラグ
     * @return Boolean 正否
     */
    public function isAlphabet( $value, $req=false )
    {
        // 正否判定
        $return = true;

        if( !preg_match( '/^[a-zA-Z]+$/', $value ) ) {
            if( !( ( $req == false ) && ( $value == '' ) ) ) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * 整数＆アルファベットバリデータ
     * @param $value 検査文字列
     * @param $req 必須フラグ
     * @return Boolean 正否
     */
    public function isNumericAlphabet( $value, $req=false )
    {
        // 正否判定
        $return = true;

        if( !preg_match( '/^[0-9a-zA-Z]+$/', $value ) ) {
            if( !( ( $req == false ) && ( $value == '' ) ) ) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * 入力内容文字数超えバリデータ
     * @param $value 検査文字列
     * @param $limit 指定文字数
     * @param $req 必須フラグ
     * @return Boolean 正否
     */
    public function isOverNumber( $value, $limit, $req=false )
    {
        // 正否判定
        $return = true;

        if( mb_strlen( $value, DEFAULT_CHARSET ) < $limit ) {
            if( !( ( $req == false ) && ( $value == '' ) ) ) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * 入力内容文字数未満バリデータ
     * @param $value 検査文字列
     * @param $limit 指定文字数
     * @param $req 必須フラグ
     * @return Boolean 正否
     */
    public function isUnderNumber( $value, $limit, $req=false )
    {
        // 正否判定
        $return = true;

        if( mb_strlen( $value, DEFAULT_CHARSET ) > $limit ) {
            if( !( ( $req == false ) && ( $value == '' ) ) ) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * 入力内容文字数丁度バリデータ
     * @param $value 検査文字列
     * @param $limit 指定文字数
     * @param $req 必須フラグ
     * @return Boolean 正否
     */
    public function isFixNumber( $value, $limit, $req=false )
    {
        // 正否判定
        $return = true;

        if( mb_strlen( $value, DEFAULT_CHARSET ) != $limit ) {
            if( !( ( $req == false ) && ( $value == '' ) ) ) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * 日付時刻バリデータ
     * @param $year 年
     * @param $month 月
     * @param $day 日
     * @param $hour 時
     * @param $min 分
     * @param $sec 秒
     * @return Boolean 正否
     */
    function isDateTime( $year, $month, $day, $hour=0, $min=0, $sec=0 )
    {
        // 正否判定
        $return = true;

        if( !checkdate( $month, $day, $year ) ) {
            $return = false;
        }
        elseif( $hour > 23 || $min > 59 || $sec > 59 ) {
            $return = false;
        }

        return $return;
    }

    /**
     * 時刻バリデータ
     * @param $hour 時
     * @param $min 分
     * @param $sec 秒
     * @return Boolean 正否
     */
    function isTime( $hour=0, $min=0, $sec=0 )
    {
        // 正否判定
        $return = true;

        if( $hour > 23 || $min > 59 || $sec > 59 ) {
            $return = false;
        }

        return $return;
    }

    /**
     * メールアドレスバリデータ
     * @param $value 検査文字列
     * @param $req 必須フラグ
     * @return Boolean 正否
     */
    public function isEmail( $value, $req=false )
    {
        // 正否判定
        $return = true;

        if( !preg_match( "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $value ) ) {
            if( !( ( $req == false ) && ( $value == '' ) ) ) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * カナバリデータ
     * @param $value 検査文字列
     * @param $req 必須フラグ
     * @return Boolean 正否
     */
    public function isKana( $value, $req=false )
    {
        // 正否判定
        $return = true;
        if( !preg_match( "/^[ァ-ヾ]+$/u", $value ) ) {
            if( !( ( $req == false ) && ( $value == '' ) ) ) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * 電話番号バリデータ
     * @param $value 検査文字列
     * @param $req 必須フラグ
     * @return Boolean 正否
     */
    public function isTel( $value, $req=false )
    {
        // 正否判定
        $return = true;
        if( !preg_match( "/^\d{2,4}-\d{2,4}-\d{2,4}$/", $value ) ) {
            if( !( ( $req == false ) && ( $value == '' ) ) ) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * 郵便番号バリデータ
     * @param $value 検査文字列
     * @param $req 必須フラグ
     * @return Boolean 正否
     */
    public function isZip( $value, $req=false )
    {
        // 正否判定
        $return = true;
        if( !preg_match( "/^\d{3}-\d{4}$/", $value ) ) {
            if( !( ( $req == false ) && ( $value == '' ) ) ) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * URLバリデータ
     * @param $value 検査文字列
     * @param $req 必須フラグ
     * @return Boolean 正否
     */
    public function isUrl( $value, $req=false )
    {
        // 正否判定
        $return = true;
        if( !preg_match( "/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/", $value ) ) {
            if( !( ( $req == false ) && ( $value == '' ) ) ) {
                $return = false;
            }
        }

        return $return;
    }
}
