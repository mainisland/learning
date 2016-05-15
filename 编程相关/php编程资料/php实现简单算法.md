#php 部分算法实现
1、冒泡排序
    原理：就是将所给数，每一个都和非本身的数比较，如果某位上的数字大于它，则放入后面，小于放前面
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

2、快速排序
    原理：将数据分割前半部分和后半部分，对前半部分和后半部分排序，然后在组合成有序的排序
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

3、选择排序
    每一趟从待排序的数据元素中选出最小（或最大）的一个元素，顺序放在已排好序的数列的最后，直到全部待排序的数据元素排完
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