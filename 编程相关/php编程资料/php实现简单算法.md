#php �����㷨ʵ��
1��ð������
    ԭ�����ǽ���������ÿһ�����ͷǱ�������Ƚϣ����ĳλ�ϵ����ִ��������������棬С�ڷ�ǰ��
    function msort($result) {
        for($i=0;$i<count($result)-1;$i++) {
            for($j=0;$j<count($result)-1-$i;$j++) {
                if($result[$j]<$result[$j+1]) {
                    $temp = $result[$j];
                    $result[$j] = $result[$j+1];
                    $result[$j+1] = $temp;
                }
            }
        }
        return $result;
    }

2����������
    ԭ�������ݷָ�ǰ�벿�ֺͺ�벿�֣���ǰ�벿�ֺͺ�벿������Ȼ������ϳ����������
    function qsort($result) {
        $len = count($result);
        if($len <= 1) {
	    return $result;
        }
        $keys[] = $result[0];
        $left = array();
        $right = array();
        for($i=1;$i<$len;$i++) {
	    if($result[$i] < $result[0]) {
	        $left[] = $result[$i];
	    } else {
	        $right[] = $result[$i];
	    }
        }
        $left = qsort($left);
        $right = qsort($right);
        return array_merge($left,$keys,$right);
    }

3��ѡ������
    ÿһ�˴Ӵ����������Ԫ����ѡ����С������󣩵�һ��Ԫ�أ�˳��������ź�������е����ֱ��ȫ�������������Ԫ������
    function ssort($result) {
        $count = count($result);
        for($i=0;$i<$count;$i++) {
	    $k = $i;
	    for($j=$i+1;$j<$count;$j++) {
	        if($result[$k] > $result[$j])
		    $k = $j;
	        if($k != $i) {
		    $tmp = $result[$i];
		    $result[$i] = $result[$k];
		    $result[$k] = $tmp;
	        }
	    }
        }
        return $result;
}