<?php

class Validator {

    /**
     * 待校验数据
     * @var array
     */
    private $_data;

    /**
     * 校验规则
     * @var array
     */
    private $_ruleList = null;

    /**
     * 校验结果
     * @var bool
     */
    private $_result = null;

    /**
     * 校验数据信息
     * @var array
     */
    private $_resultInfo = array();

    /**
     * 构造函数
     * @param array $data 待校验数据
     */
    public function __construct($data = null)
    {
        if ($data) {
            $this->_data = $data;
        }
    }

    /**
     * 设置校验规则
     * @param string $var  带校验项key
     * @param mixed  $rule 校验规则
     * @return void
     */
    public function setRule($var, $rule)
    {
        $this->_ruleList[$var] = $rule;
    }

    /**
     * 检验数据
     * @param  array $data
     * <code>
     *  $data = array('nickname' => 'heno' , 'realname' => 'steven', 'age' => 25);
     *  $validator = new Validator($data);
     *  $validator->setRule('nickname', 'required');
     *  $validator->setRule('realname', array('lenght' => array(1,4), 'required'));
     *  $validator->setRule('age', array('required', 'digit'));
     *  $result = $validator->validate();
     *  var_dump($validator->getResultInfo());
     * </code>
     * @return bool
     */
    public function validate($data = null)
    {
        $result = true;

        /* 如果没有设置校验规则直接返回 true */
        if ($this->_ruleList === null || !count($this->_ruleList)) {
            return $result;
        }

        /* 已经设置规则，则对规则逐条进行校验 */
        foreach ($this->_ruleList as $ruleKey => $ruleItem) {

            /* 如果检验规则为单条规则 */
            if (!is_array($ruleItem)) {
                $ruleItem = trim($ruleItem);
                if (method_exists($this, $ruleItem)) {

                    /* 校验数据，保存校验结果 */
                    $tmpResult = $this->$ruleItem($ruleKey);
                    if (!$tmpResult) {
                        $this->_resultInfo[$ruleKey][$ruleItem] = $tmpResult;
                        $result = false;
                    }
                }
                continue;
            }

            /* 校验规则为多条 */
            foreach ($ruleItem  as $ruleItemKey => $rule) {

                if (!is_array($rule)) {
                    $rule = trim($rule);
                    if (method_exists($this, $rule)) {

                        /* 校验数据，设置结果集 */
                        $tmpResult = $this->$rule($ruleKey);
                        if (!$tmpResult) {
                            $this->_resultInfo[$ruleKey][$rule] = $tmpResult;
                            $result = false;
                        }
                    }
                } else {
                    if (method_exists($this, $ruleItemKey)) {

                        /* 校验数据，设置结果集 */
                        $tmpResult = $this->$ruleItemKey($ruleKey, $rule);
                        if (!$tmpResult) {
                            $this->_resultInfo[$ruleKey][$ruleItemKey] = $tmpResult;
                            $result = false;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * 获取校验结果数据
     * @return [type] [description]
     */
    public function getResultInfo()
    {
        return $this->_resultInfo;
    }

    /**
     * 校验必填参数
     * @param  string $varName 校验项
     * @return bool
     */
    public function required($varName)
    {
        $result = false;
        if (is_array($this->_data) && isset($this->_data[$varName])) {
            $result = true;
        }
        return $result;
    }


    /**
     * 校验参数长度
     *
     * @param  string $varName 校验项
     * @param  array $lengthData  array($minLen, $maxLen)
     * @return bool
     */
    public function length($varName, $lengthData)
    {
        $result = true;

        /* 如果该项没有设置，默认为校验通过 */
        if ($this->required($varName)) {
            $varLen = mb_strlen($this->_data[$varName]);
            $minLen = $lengthData[0];
            $maxLen = $lengthData[1];
            if ($varLen < $minLen || $varLen > $maxLen) {
                $result = true;
            }
        }
        return $result;
    }


    /**
     * 校验邮件
     * @param  string $varName 校验项
     * @return bool
     */
    public function email($varName)
    {
        $result = true;

        /* 如果该项没有设置，默认为校验通过 */
        if ($this->required($varName)) {
            $email = trim($this->_data[$varName]);
            if (preg_match('/^[-\w]+?@[-\w.]+?$/', $email)) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * 校验手机
     * @param  string $varName 校验项
     * @return bool
     */
    public function mobile($varName)
    {
        $result = true;

        /* 如果该项没有设置，默认为校验通过 */
        if ($this->required($varName)) {
            $mobile = trim($this->_data[$varName]);
            if (!preg_match('/^1[3458]\d{10}$/', $mobile)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * 校验参数为数字
     * @param  string $varName 校验项
     * @return bool
     */
    public function digit($varName)
    {
        $result = false;
        if ($this->required($varName) && is_numeric($this->_data[$varName])) {
            $result = true;
        }
        return $result;
    }


    /**
     * 校验参数为身份证
     * @param  string $varName 校验项
     * @return bool
     */
    public function ID($ID)
    {

    }


    /**
     * 校验参数为URL
     * @param  string $varName 校验项
     * @return bool
     */
    public function url($url)
    {
        $result = true;

        /* 如果该项没有设置，默认为校验通过 */
        if ($this->required($varName)) {
            $url = trim($this->_data[$varName]);
            if(!preg_match('/^(http[s]?::)?\w+?(\.\w+?)$/', $url)) {
                $result = false;
            }
        }
        return $result;
    }
}
