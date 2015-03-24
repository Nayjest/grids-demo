<?php namespace App\Http\Controllers;

use App\User;
use Grids;
use HTML;
use Illuminate\Support\Facades\Config;
use Nayjest\Grids\Components\Laravel5\Pager;
use Nayjest\Grids\Components\OneCellRow;
use Nayjest\Grids\Components\TFoot;
use Nayjest\Grids\Components\THead;
use Nayjest\Grids\Components\TotalsRow;
use Nayjest\Grids\EloquentDataProvider;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\FilterConfig;
use Nayjest\Grids\Grid;
use Nayjest\Grids\GridConfig;

class DemoController extends Controller
{

    public function getExample1()
    {
        $cfg = [
            'src' => 'App\User',
            'columns' => [
                'id',
                'name',
                'email',
                'country'
            ]
        ];
        $grid = Grids::make($cfg);
        $text = "<h1>Basic grid example</h1>";
        return view('demo.default', compact('grid', 'text'));
    }

    public function getExample2()
    {
        $text = "<h1>Loading grid config</h1>";
        $grid = Grids::make(Config::get('grids.example2'));
        return view('demo.default', compact('grid', 'text'));
    }

    public function getExample3()
    {
        $cfg = (new GridConfig())
            ->setDataProvider(
                new EloquentDataProvider(
                    (new User)->newQuery()
                )
            )
            ->setColumns([
                new FieldConfig('id'),
                new FieldConfig('name'),
                new FieldConfig('email'),
                new FieldConfig('country'),
            ])
            ;
        $grid = new Grid($cfg);
        $text = "<h1>Constructing grid programmatically</h1>";
        return view('demo.default', compact('grid', 'text'));
    }

    public function getExample4()
    {
        $grid = new Grid(
            (new GridConfig)
                ->setDataProvider(
                    new EloquentDataProvider(User::query())
                )
                ->setName('example_grid1')
                ->setPageSize(15)
                ->setColumns([
                    (new FieldConfig)
                        ->setName('id')
                        ->setLabel('ID')
                        ->setSortable(true)
                        ->setSorting(Grid::SORT_ASC)
                    ,
                    (new FieldConfig)
                        ->setName('name')
                        ->setLabel('Name')
                        ->setCallback(function ($val) {
                            return "<span class='glyphicon glyphicon-user'></span>{$val}";
                        })
                        ->setSortable(true)
                        ->addFilter(
                            (new FilterConfig)
                                ->setOperator(FilterConfig::OPERATOR_LIKE)
                        )
                    ,
                    (new FieldConfig)
                        ->setName('email')
                        ->setLabel('Email')
                        ->setSortable(true)
                        ->setCallback(function ($val) {
                            $icon = '<span class="glyphicon glyphicon-envelope"></span>&nbsp;';
                            return $icon . HTML::link("mailto:$val", $val);
                        })
                        ->addFilter(
                            (new FilterConfig)
                                ->setOperator(FilterConfig::OPERATOR_LIKE)
                        )
                    ,
                    (new FieldConfig)
                        ->setName('phone_number')
                        ->setLabel('Phone')
                        ->setSortable(true)
                        ->addFilter(
                            (new FilterConfig)
                                ->setOperator(FilterConfig::OPERATOR_LIKE)
                        )
                    ,
                    (new FieldConfig)
                        ->setName('country')
                        ->setLabel('Country')
                        ->setSortable(true)
                    ,
                    (new FieldConfig)
                        ->setName('company')
                        ->setLabel('Company')
                        ->setSortable(true)
                        ->addFilter(
                            (new FilterConfig)
                                ->setOperator(FilterConfig::OPERATOR_LIKE)
                        )
                    ,
                    (new FieldConfig)
                        ->setName('birthday')
                        ->setLabel('Birthday')
                        ->setSortable(true)
                    ,
                    (new FieldConfig)
                        ->setName('posts_count')
                        ->setLabel('Posts')
                        ->setSortable(true)
                    ,
                    (new FieldConfig)
                        ->setName('comments_count')
                        ->setLabel('Comments')
                        ->setSortable(true)
                    ,
                ])
                ->setComponents([
                    (new THead)
                    ,
                    (new TFoot)
                        ->setComponents([
                            (new TotalsRow(['posts_count', 'comments_count'])),
                            (new TotalsRow(['posts_count', 'comments_count']))
                                ->setFieldOperations([
                                    'posts_count' => TotalsRow::OPERATION_AVG,
                                    'comments_count' => TotalsRow::OPERATION_AVG,
                                ])
                            ,
                            (new OneCellRow)
                                ->setComponents([
                                    new Pager,
                                ])
                        ])
                    ,
                ])
        );
        return view('demo.default', compact('grid'));
    }

}
