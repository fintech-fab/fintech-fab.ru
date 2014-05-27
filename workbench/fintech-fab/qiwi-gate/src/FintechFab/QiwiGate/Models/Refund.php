<?php

namespace FintechFab\QiwiGate\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $bill_id
 * @property integer $refund_id
 * @property string  $amount
 * @property string  $status
 * @property string  $created_at
 * @property string  $updated_at
 *
 * @property Bill    $bill
 *
 * @method static Refund find()
 * @method static Refund where()
 * @method static Refund first()
 *
 * @method static Refund whereBillId($id)
 * @method static Refund whereRefundId($id)
 * @method static Refund whereStatus($status)
 */
class Refund extends Eloquent
{
	const C_STATUS_PROCESSING = 'processing';
	const C_STATUS_SUCCESS = 'success';

	protected $fillable = array(
		'bill_id', 'refund_id', 'amount', 'status',
	);
	protected $table = 'bills_refund';
	protected $connection = 'qiwiGate';

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Bill
	 */
	public function bill()
	{
		return $this->belongsTo('\FintechFab\QiwiGate\Models\Bill');
	}

	/**
	 * Возврат существует?
	 *
	 * @param $bill_id
	 * @param $refund_id
	 *
	 * @return bool
	 */
	public static function isRefundExist($bill_id, $refund_id)
	{
		$existRefund = Refund::whereBillId($bill_id)
			->whereRefundId($refund_id)
			->first();
		if ($existRefund != null) {
			return true;
		}

		return false;
	}

	/**
	 * Возврат в процессе?
	 *
	 * @return bool
	 */
	public function isProcessing()
	{
		return $this->status == self::C_STATUS_PROCESSING;
	}

	/**
	 * Проводит возварт по прошествии минуты
	 * Отдаёт новый счётё
	 *
	 * @return Refund
	 */
	public function doSuccess()
	{
		$date = date('Y-m-d H:i:s', time() - 60);
		$dateRefund = $this->updated_at; //даём минуту для прохождения оплаты
		if ($date > $dateRefund) {
			Refund::find($this->id)
				->whereStatus(self::C_STATUS_PROCESSING)
				->update(array('status' => self::C_STATUS_SUCCESS));
		}

		return Refund::find($this->id);
	}
}